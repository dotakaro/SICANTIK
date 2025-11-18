# Part of Odoo. See LICENSE file for full copyright and licensing details.
"""
Compatibility layer for Odoo 18.0 CE and 18.4+ Enterprise
Provides Constraint class for Odoo 18.0 CE compatibility

This module ensures that models.Constraint() calls work in both:
- Odoo 18.0 CE: Converts to _sql_constraints format
- Odoo 18.4+: Uses native Constraint class
"""

import logging
from odoo import models

_logger = logging.getLogger(__name__)

# Check if Constraint is already available (Odoo 18.4+)
if not hasattr(models, 'Constraint'):
    # Odoo 18.0 CE compatibility: Create Constraint class that stores constraint info
    # The constraint will be converted to _sql_constraints in model's __init_subclass__
    class Constraint:
        """
        Compatibility wrapper for Constraint class in Odoo 18.0 CE
        Stores constraint information that will be converted to _sql_constraints
        """
        def __init__(self, definition, message=None):
            """
            Args:
                definition: SQL constraint definition 
                           - For UNIQUE: 'unique(template_name, lang_code, wa_account_id)' or 'UNIQUE(name, wa_template_id)'
                           - For CHECK: "CHECK (channel_type = 'channel' OR channel_type = 'whatsapp' OR group_public_id IS NULL)"
                message: Error message (required)
            
            Signature: Constraint(definition, message)
            """
            self.definition = definition
            self.message = message or "Constraint violation"
            # Generate constraint name from attribute name (will be set by __init_subclass__)
            self.name = None  # Will be set when converting to _sql_constraints
        
        def to_sql_constraint(self):
            """Convert to _sql_constraints tuple format"""
            return (self.name, self.definition, self.message)
    
    # Monkey patch models.Constraint for compatibility
    models.Constraint = Constraint
    
    # Patch BaseModel to automatically convert Constraint instances to _sql_constraints
    _original_init_subclass = models.BaseModel.__init_subclass__
    
    def _patched_init_subclass(cls, **kwargs):
        """Patch __init_subclass__ to convert Constraint instances to _sql_constraints"""
        _original_init_subclass(**kwargs)
        
        # Collect all Constraint instances from class attributes
        constraints = []
        for attr_name in dir(cls):
            if attr_name.startswith('_') and not attr_name.startswith('__'):
                try:
                    attr_value = getattr(cls, attr_name, None)
                    if isinstance(attr_value, Constraint):
                        # Use attribute name as constraint name
                        attr_value.name = attr_name
                        constraints.append(attr_value.to_sql_constraint())
                        # Remove the Constraint instance from class (it's now in _sql_constraints)
                        delattr(cls, attr_name)
                except (AttributeError, TypeError):
                    # Skip if attribute can't be accessed or deleted
                    pass
        
        # If we found constraints, add them to _sql_constraints
        if constraints:
            # Get existing _sql_constraints or create new list
            existing_constraints = getattr(cls, '_sql_constraints', []) or []
            # Combine and update
            cls._sql_constraints = existing_constraints + constraints
            _logger.debug(f"Converted {len(constraints)} Constraint(s) to _sql_constraints for {cls._name}")
    
    models.BaseModel.__init_subclass__ = classmethod(_patched_init_subclass)
    _logger.info("Compatibility layer: Added Constraint class to odoo.models for Odoo 18.0 CE")
else:
    _logger.debug("Constraint class already available in odoo.models (Odoo 18.4+)")

