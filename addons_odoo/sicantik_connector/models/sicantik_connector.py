# -*- coding: utf-8 -*-

import base64
import requests
import time
import logging
import xml.etree.ElementTree as ET
from datetime import datetime, timedelta
from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError

_logger = logging.getLogger(__name__)


class SicantikConnector(models.Model):
    """
    SICANTIK API Connector Service
    
    Handles all API communications with SICANTIK server including:
    - Data synchronization
    - Expiry date sync (workaround solution)
    - Error handling and retry logic
    - Rate limiting
    """
    _name = 'sicantik.connector'
    _description = 'SICANTIK API Connector Service'
    _rec_name = 'name'
    
    name = fields.Char(string='Connector Name', required=True, default='SICANTIK Connector')
    config_id = fields.Many2one('sicantik.config', string='Configuration', required=True)
    active = fields.Boolean(string='Active', default=True)
    
    # Statistics
    last_sync_date = fields.Datetime(string='Last Sync Date', readonly=True)
    last_expiry_sync_date = fields.Datetime(string='Last Expiry Sync Date', readonly=True)
    total_permits_synced = fields.Integer(string='Total Permits Synced', readonly=True)
    total_expiry_synced = fields.Integer(string='Total Expiry Synced', readonly=True)
    last_sync_duration = fields.Float(string='Last Sync Duration (seconds)', readonly=True)
    last_expiry_sync_duration = fields.Float(string='Last Expiry Sync Duration (seconds)', readonly=True)
    
    def _make_api_request(self, endpoint, params=None, method='GET', timeout=None):
        """
        Make API request to SICANTIK server
        
        Args:
            endpoint (str): API endpoint
            params (dict): Query parameters
            method (str): HTTP method
            timeout (int): Request timeout
        
        Returns:
            dict/list: API response data
        
        Raises:
            UserError: If request fails
        """
        self.ensure_one()
        
        if not timeout:
            timeout = self.config_id.api_timeout
        
        url = self.config_id.get_api_url(endpoint)
        
        try:
            _logger.info(f'API Request: {method} {url} params={params} timeout={timeout}s')
            
            if method == 'GET':
                response = requests.get(url, params=params, timeout=timeout)
            else:
                raise NotImplementedError(f'HTTP method {method} not implemented')
            
            _logger.info(f'Response status: {response.status_code}')
            response.raise_for_status()
            
            # Check if response is empty
            if not response.text or response.text.strip() == '':
                raise ValueError('API mengembalikan response kosong')
            
            # Check content type and parse accordingly
            content_type = response.headers.get('Content-Type', '')
            _logger.info(f'Content-Type: {content_type}')
            
            if 'xml' in content_type.lower() or response.text.strip().startswith('<?xml'):
                # Parse XML response
                _logger.info(f'Parsing XML response (length: {len(response.text)})')
                try:
                    root = ET.fromstring(response.text)
                    
                    # Check if it's a list of items (multiple records)
                    items = root.findall('.//item')
                    if items:
                        # Multiple items - convert to list of dicts
                        data = []
                        for item in items:
                            item_dict = {}
                            for child in item:
                                item_dict[child.tag] = child.text
                            data.append(item_dict)
                        _logger.info(f'XML parsed: {len(data)} items')
                    else:
                        # Single item - convert to dict
                        data = {}
                        for child in root.iter():
                            if child.text and child.text.strip():
                                data[child.tag] = child.text
                        _logger.info(f'XML parsed: single item with {len(data)} fields')
                    
                    return data
                    
                except ET.ParseError as xml_err:
                    _logger.error(f'XML parsing error: {xml_err}. Response: {response.text[:500]}')
                    raise ValueError(f'API tidak mengembalikan XML valid: {str(xml_err)}')
            else:
                # Try to parse JSON
                try:
                    data = response.json()
                    _logger.info(f'JSON parsed: {len(data) if isinstance(data, list) else "object"}')
                    return data
                except ValueError as json_err:
                    _logger.error(f'JSON parsing error. Response: {response.text[:500]}')
                    raise ValueError(f'API tidak mengembalikan JSON valid. Response: {response.text[:100]}')
        
        except requests.exceptions.Timeout:
            error_msg = f'API request timeout after {timeout} seconds'
            _logger.error(error_msg)
            raise UserError(error_msg)
        
        except requests.exceptions.RequestException as e:
            error_msg = f'API request failed: {str(e)}'
            _logger.error(error_msg)
            raise UserError(error_msg)
        
        except ValueError as e:
            error_msg = f'Invalid response: {str(e)}'
            _logger.error(error_msg)
            raise UserError(error_msg)
    
    def sync_permits(self, limit=None, offset=0):
        """
        Sync permits from SICANTIK API
        
        Args:
            limit (int): Number of records to fetch
            offset (int): Starting offset
        
        Returns:
            dict: Sync statistics
        """
        self.ensure_one()
        start_time = time.time()
        
        if not limit:
            limit = self.config_id.sync_limit
        
        _logger.info(f'Starting permit sync: limit={limit}, offset={offset}')
        
        try:
            # Fetch permits from API
            data = self._make_api_request(
                'listpermohonanterbit',
                params={'limit': limit, 'offset': offset}
            )
            
            if not data:
                _logger.info('No permits to sync')
                return {'synced': 0, 'skipped': 0, 'failed': 0}
            
            synced = 0
            skipped = 0
            failed = 0
            
            for permit_data in data:
                try:
                    result = self._process_permit_data(permit_data)
                    if result == 'synced':
                        synced += 1
                    elif result == 'skipped':
                        skipped += 1
                except Exception as e:
                    _logger.error(f'Error processing permit {permit_data.get("pendaftaran_id")}: {str(e)}')
                    failed += 1
            
            # Update statistics
            duration = time.time() - start_time
            self.write({
                'last_sync_date': fields.Datetime.now(),
                'total_permits_synced': self.total_permits_synced + synced,
                'last_sync_duration': duration
            })
            
            _logger.info(f'Permit sync completed: synced={synced}, skipped={skipped}, failed={failed}, duration={duration:.2f}s')
            
            return {
                'synced': synced,
                'skipped': skipped,
                'failed': failed,
                'duration': duration
            }
        
        except Exception as e:
            _logger.error(f'Fatal error in permit sync: {str(e)}')
            raise UserError(f'Permit sync failed: {str(e)}')
    
    def _process_permit_data(self, data):
        """
        Process single permit data from API
        
        Args:
            data (dict): Permit data from API
        
        Returns:
            str: 'synced', 'skipped', or 'failed'
        """
        registration_id = data.get('pendaftaran_id')
        if not registration_id:
            return 'failed'
        
        # Check if permit already exists
        existing_permit = self.env['sicantik.permit'].search([
            ('registration_id', '=', registration_id)
        ], limit=1)
        
        if existing_permit:
            # Update existing permit
            existing_permit.write({
                'applicant_name': data.get('n_pemohon'),
                'permit_type_name': data.get('n_perizinan'),
                'permit_number': data.get('no_surat'),
                'last_sync_date': fields.Datetime.now()
            })
            return 'skipped'
        else:
            # Create new permit
            self.env['sicantik.permit'].create({
                'registration_id': registration_id,
                'applicant_name': data.get('n_pemohon'),
                'permit_type_name': data.get('n_perizinan'),
                'permit_number': data.get('no_surat'),
                'status': 'active',
                'last_sync_date': fields.Datetime.now()
            })
            return 'synced'
    
    def sync_expiry_dates_workaround(self, max_permits=None):
        """
        WORKAROUND: Sync expiry dates using two-step API process
        
        This is a temporary solution until API is updated to include
        d_berlaku_izin in listpermohonanterbit response.
        
        Process:
        1. Get all permits without expiry date
        2. For each permit, call cekperizinan API
        3. Extract d_berlaku_izin and update permit
        
        Performance: ~0.15 seconds per permit
        
        Args:
            max_permits (int): Maximum permits to process (for testing)
        
        Returns:
            dict: Sync statistics
        """
        self.ensure_one()
        start_time = time.time()
        
        _logger.info('='*80)
        _logger.info('WORKAROUND: Starting expiry date sync')
        _logger.info('='*80)
        
        try:
            # Find permits without expiry date
            permits_to_sync = self.env['sicantik.permit'].search([
                ('expiry_date', '=', False),
                ('status', '=', 'active'),
                ('permit_number', '!=', False)
            ])
            
            if max_permits:
                permits_to_sync = permits_to_sync[:max_permits]
            
            total_permits = len(permits_to_sync)
            _logger.info(f'Found {total_permits} permits without expiry date')
            
            if total_permits == 0:
                _logger.info('No permits to sync')
                return {
                    'success': True,
                    'total': 0,
                    'synced': 0,
                    'failed': 0,
                    'duration': 0
                }
            
            synced_count = 0
            failed_count = 0
            
            for index, permit in enumerate(permits_to_sync, 1):
                try:
                    _logger.info(f'Processing {index}/{total_permits}: {permit.permit_number}')
                    
                    # Get expiry date from API
                    expiry_data = self._get_permit_expiry_workaround(permit.permit_number)
                    
                    if expiry_data and expiry_data.get('d_berlaku_izin'):
                        permit.write({
                            'expiry_date': expiry_data['d_berlaku_izin'],
                            'last_sync_date': fields.Datetime.now()
                        })
                        synced_count += 1
                        _logger.info(f'✅ Synced expiry: {expiry_data["d_berlaku_izin"]}')
                    else:
                        failed_count += 1
                        _logger.warning(f'⚠️ No expiry date found')
                    
                    # Rate limiting
                    if self.config_id.rate_limit_enabled:
                        time.sleep(1.0 / self.config_id.rate_limit_requests)
                    
                    # Progress update every 50 permits
                    if index % 50 == 0:
                        progress = (index / total_permits) * 100
                        elapsed = time.time() - start_time
                        estimated_total = (elapsed / index) * total_permits
                        remaining = estimated_total - elapsed
                        
                        _logger.info(f'Progress: {progress:.1f}% ({index}/{total_permits})')
                        _logger.info(f'Elapsed: {elapsed:.1f}s, Remaining: {remaining:.1f}s')
                
                except Exception as e:
                    failed_count += 1
                    _logger.error(f'❌ Error: {str(e)}')
                    continue
            
            # Update statistics
            duration = time.time() - start_time
            self.write({
                'last_expiry_sync_date': fields.Datetime.now(),
                'total_expiry_synced': self.total_expiry_synced + synced_count,
                'last_expiry_sync_duration': duration
            })
            
            _logger.info('='*80)
            _logger.info(f'WORKAROUND: Expiry sync completed')
            _logger.info(f'Total: {total_permits}, Synced: {synced_count}, Failed: {failed_count}')
            _logger.info(f'Duration: {duration:.2f} seconds')
            _logger.info('='*80)
            
            return {
                'success': True,
                'total': total_permits,
                'synced': synced_count,
                'failed': failed_count,
                'duration': duration
            }
        
        except Exception as e:
            _logger.error(f'Fatal error in expiry sync: {str(e)}')
            raise UserError(f'Expiry sync failed: {str(e)}')
    
    def _get_permit_expiry_workaround(self, permit_number):
        """
        WORKAROUND: Get expiry date for single permit
        
        Uses cekperizinan endpoint which requires base64 encoded permit number
        
        Args:
            permit_number (str): Permit number (no_surat)
        
        Returns:
            dict: Permit data including d_berlaku_izin, or None
        """
        try:
            # Encode permit number to base64
            no_izin_encoded = base64.b64encode(
                permit_number.encode('utf-8')
            ).decode('utf-8')
            
            # Call API
            data = self._make_api_request(
                'cekperizinan',
                params={'no_izin': no_izin_encoded},
                timeout=10
            )
            
            # Handle both single object and array response
            if isinstance(data, list) and len(data) > 0:
                return data[0]
            elif isinstance(data, dict):
                return data
            else:
                return None
        
        except Exception as e:
            _logger.warning(f'Error getting expiry for {permit_number}: {str(e)}')
            return None
    
    @api.model
    def cron_sync_expiry_dates(self):
        """
        Cron job to sync expiry dates
        Run daily at 02:00 AM
        """
        _logger.info('Starting scheduled expiry date sync...')
        
        connector = self.search([('active', '=', True)], limit=1)
        if not connector:
            _logger.error('No active connector found')
            return
        
        try:
            result = connector.sync_expiry_dates_workaround()
            
            if result['success']:
                _logger.info(f'Scheduled expiry sync completed: {result["synced"]} permits synced')
        
        except Exception as e:
            _logger.error(f'Scheduled expiry sync error: {str(e)}')
    
    def action_sync_permits(self):
        """Manual action to sync permits"""
        self.ensure_one()
        
        result = self.sync_permits()
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Sync Completed',
                'message': f'Synced: {result["synced"]}, Skipped: {result["skipped"]}, Failed: {result["failed"]}',
                'type': 'success' if result['synced'] > 0 else 'warning',
                'sticky': False,
            }
        }
    
    def action_test_expiry_sync(self):
        """Manual action to test expiry sync"""
        self.ensure_one()
        
        result = self.sync_expiry_dates_workaround(max_permits=10)
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Expiry Sync Test Completed',
                'message': f'Synced: {result["synced"]}, Failed: {result["failed"]}, Duration: {result["duration"]:.2f}s',
                'type': 'success' if result['synced'] > 0 else 'warning',
                'sticky': False,
            }
        }
    
    def action_sync_all_expiry(self):
        """Open wizard for full expiry sync"""
        self.ensure_one()
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Sync All Expiry Dates',
            'res_model': 'sicantik.expiry.sync.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_connector_id': self.id}
        }

