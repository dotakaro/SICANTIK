# -*- coding: utf-8 -*-

import logging
from datetime import datetime, timedelta
from odoo import http
from odoo.http import request

_logger = logging.getLogger(__name__)

# Cache storage (in-memory, akan reset saat restart server)
_cache = {}
_cache_ttl = timedelta(minutes=5)  # Cache TTL: 5 menit


class DashboardController(http.Controller):
    """Controller untuk API endpoint dashboard"""

    def _get_cache_key(self, user_id):
        """Generate cache key berdasarkan user ID"""
        return f"dashboard_stats_{user_id}"

    def _get_cached_stats(self, cache_key):
        """Ambil data dari cache jika masih valid"""
        if cache_key in _cache:
            cached_data, cached_time = _cache[cache_key]
            if datetime.now() - cached_time < _cache_ttl:
                _logger.debug(f"Cache hit untuk {cache_key}")
                return cached_data
            else:
                # Cache expired, hapus
                del _cache[cache_key]
                _logger.debug(f"Cache expired untuk {cache_key}")
        return None

    def _set_cached_stats(self, cache_key, data):
        """Simpan data ke cache"""
        _cache[cache_key] = (data, datetime.now())
        _logger.debug(f"Cache set untuk {cache_key}")

    @http.route('/sicantik/dashboard/stats', type='json', auth='user', methods=['POST'])
    def get_dashboard_stats(self, year_filter='all'):
        """
        Endpoint untuk mengambil data statistik dashboard
        Dengan caching 5 menit untuk performa optimal
        
        Args:
            year_filter: 'all' untuk semua tahun, atau tahun spesifik (2021, 2022, dll)
        
        Returns:
            dict: Dictionary berisi semua statistik (permit, document, whatsapp)
        """
        try:
            user_id = request.env.user.id
            cache_key = f"{self._get_cache_key(user_id)}_{year_filter}"
            
            # Cek cache terlebih dahulu
            cached_result = self._get_cached_stats(cache_key)
            if cached_result:
                return cached_result
            
            # Jika tidak ada di cache, hitung statistik
            stats_model = request.env['sicantik.dashboard.stats']
            
            # Ambil semua statistik dengan filter tahun
            permit_stats = stats_model.get_permit_stats(year_filter=year_filter)
            document_stats = stats_model.get_document_stats(year_filter=year_filter)
            whatsapp_stats = stats_model.get_whatsapp_stats(year_filter=year_filter)
            
            result = {
                'success': True,
                'permit_stats': permit_stats,
                'document_stats': document_stats,
                'whatsapp_stats': whatsapp_stats,
            }
            
            # Simpan ke cache
            self._set_cached_stats(cache_key, result)
            
            return result
        except Exception as e:
            _logger.error(f"Error getting dashboard stats: {str(e)}", exc_info=True)
            return {
                'success': False,
                'error': str(e),
            }
