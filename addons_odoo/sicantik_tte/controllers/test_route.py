# -*- coding: utf-8 -*-

from odoo import http
import logging

_logger = logging.getLogger(__name__)


class TestRouteController(http.Controller):
    """Test controller untuk memastikan routing bekerja"""
    
    @http.route('/sicantik/test', type='http', auth='public', methods=['GET'], csrf=False, website=True)
    def test_route(self, **kwargs):
        """Test route untuk memastikan controller ter-load"""
        _logger.info('TEST ROUTE CALLED - Controller is working!')
        return "<h1>Test Route Works! Controller is loaded correctly.</h1>"

