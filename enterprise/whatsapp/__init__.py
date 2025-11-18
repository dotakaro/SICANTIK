# Part of Odoo. See LICENSE file for full copyright and licensing details.

# Import compatibility layer FIRST before any models to ensure Constraint class is available
from . import compatibility

from . import controller
from . import models
from . import tools
from . import wizard
