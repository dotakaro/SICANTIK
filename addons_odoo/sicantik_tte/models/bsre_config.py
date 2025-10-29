# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import requests
import base64
import logging

_logger = logging.getLogger(__name__)


class BsreConfig(models.Model):
    """
    Konfigurasi untuk BSRE (Badan Siber dan Sandi Negara) TTE API
    """
    _name = 'bsre.config'
    _description = 'Konfigurasi BSRE TTE'
    _rec_name = 'name'
    
    name = fields.Char(
        string='Nama Konfigurasi',
        required=True,
        default='BSRE Production'
    )
    active = fields.Boolean(
        string='Aktif',
        default=True
    )
    
    # BSRE API Configuration
    api_url = fields.Char(
        string='URL API BSRE',
        required=True,
        default='https://api.bsre.id/v1',
        help='Base URL untuk BSRE API'
    )
    api_key = fields.Char(
        string='API Key',
        required=True,
        help='API Key dari BSRE'
    )
    api_secret = fields.Char(
        string='API Secret',
        required=True,
        help='API Secret dari BSRE'
    )
    
    # Certificate Configuration
    certificate_id = fields.Char(
        string='Certificate ID',
        help='ID sertifikat digital yang digunakan'
    )
    certificate_owner = fields.Char(
        string='Pemilik Sertifikat',
        help='Nama pemilik sertifikat digital'
    )
    certificate_valid_until = fields.Date(
        string='Sertifikat Berlaku Sampai',
        help='Tanggal kadaluarsa sertifikat'
    )
    
    # Signature Configuration
    signature_position = fields.Selection([
        ('bottom_left', 'Kiri Bawah'),
        ('bottom_right', 'Kanan Bawah'),
        ('top_left', 'Kiri Atas'),
        ('top_right', 'Kanan Atas'),
        ('center', 'Tengah'),
    ], string='Posisi Tanda Tangan', default='bottom_right')
    
    signature_page = fields.Selection([
        ('first', 'Halaman Pertama'),
        ('last', 'Halaman Terakhir'),
        ('all', 'Semua Halaman'),
    ], string='Halaman Tanda Tangan', default='last')
    
    signature_visible = fields.Boolean(
        string='Tanda Tangan Visible',
        default=True,
        help='Tampilkan tanda tangan visual di PDF'
    )
    
    # Timeout & Retry
    api_timeout = fields.Integer(
        string='API Timeout (detik)',
        default=60,
        help='Timeout untuk request API'
    )
    max_retry = fields.Integer(
        string='Max Retry',
        default=3,
        help='Maksimal retry jika request gagal'
    )
    
    # Connection Status
    connection_status = fields.Selection([
        ('disconnected', 'Terputus'),
        ('connected', 'Terhubung'),
        ('error', 'Error')
    ], string='Status Koneksi', default='disconnected', readonly=True)
    
    last_error = fields.Text(
        string='Error Terakhir',
        readonly=True
    )
    
    # Statistics
    total_signatures = fields.Integer(
        string='Total Tanda Tangan',
        default=0,
        readonly=True
    )
    successful_signatures = fields.Integer(
        string='Tanda Tangan Berhasil',
        default=0,
        readonly=True
    )
    failed_signatures = fields.Integer(
        string='Tanda Tangan Gagal',
        default=0,
        readonly=True
    )
    last_signature_date = fields.Datetime(
        string='Tanda Tangan Terakhir',
        readonly=True
    )
    
    @api.constrains('active')
    def _check_one_active_config(self):
        """Ensure only one active BSRE configuration"""
        if self.active:
            active_configs = self.search([('active', '=', True), ('id', '!=', self.id)])
            if active_configs:
                raise ValidationError('Hanya satu konfigurasi BSRE yang dapat aktif pada satu waktu')
    
    def _make_api_request(self, endpoint, method='POST', data=None, files=None):
        """
        Make API request to BSRE
        
        Args:
            endpoint (str): API endpoint
            method (str): HTTP method
            data (dict): Request data
            files (dict): Files to upload
        
        Returns:
            dict: API response
        """
        self.ensure_one()
        
        url = f'{self.api_url}/{endpoint}'
        
        headers = {
            'X-API-Key': self.api_key,
            'X-API-Secret': self.api_secret,
        }
        
        try:
            _logger.info(f'BSRE API Request: {method} {url}')
            
            if method == 'POST':
                if files:
                    response = requests.post(url, headers=headers, data=data, files=files, timeout=self.api_timeout)
                else:
                    headers['Content-Type'] = 'application/json'
                    response = requests.post(url, headers=headers, json=data, timeout=self.api_timeout)
            elif method == 'GET':
                response = requests.get(url, headers=headers, params=data, timeout=self.api_timeout)
            else:
                raise NotImplementedError(f'HTTP method {method} not implemented')
            
            _logger.info(f'BSRE API Response: {response.status_code}')
            response.raise_for_status()
            
            return response.json()
            
        except requests.exceptions.Timeout:
            error_msg = f'API request timeout after {self.api_timeout} seconds'
            _logger.error(error_msg)
            raise UserError(error_msg)
        
        except requests.exceptions.RequestException as e:
            error_msg = f'API request failed: {str(e)}'
            _logger.error(error_msg)
            raise UserError(error_msg)
        
        except Exception as e:
            error_msg = f'Unexpected error: {str(e)}'
            _logger.error(error_msg)
            raise UserError(error_msg)
    
    def action_test_connection(self):
        """Test koneksi ke BSRE API"""
        self.ensure_one()
        
        try:
            # Test connection by getting certificate info
            result = self._make_api_request('certificate/info', method='GET')
            
            if result.get('success'):
                self.write({
                    'connection_status': 'connected',
                    'last_error': False,
                    'certificate_id': result.get('certificate_id'),
                    'certificate_owner': result.get('owner'),
                    'certificate_valid_until': result.get('valid_until'),
                })
                
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Koneksi Berhasil',
                        'message': f'Berhasil terhubung ke BSRE API. Sertifikat: {result.get("owner")}',
                        'type': 'success',
                        'sticky': False,
                    }
                }
            else:
                raise UserError('BSRE API mengembalikan status tidak berhasil')
                
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'BSRE connection test failed: {error_msg}')
            
            self.write({
                'connection_status': 'error',
                'last_error': error_msg
            })
            
            raise UserError(f'Koneksi gagal: {error_msg}')
    
    def sign_document(self, document_data, document_name):
        """
        Sign document dengan BSRE TTE
        
        Args:
            document_data (bytes): Binary data PDF
            document_name (str): Nama dokumen
        
        Returns:
            dict: Result with success status, signed data, and metadata
        """
        self.ensure_one()
        
        try:
            # Prepare request data
            data = {
                'certificate_id': self.certificate_id,
                'signature_position': self.signature_position,
                'signature_page': self.signature_page,
                'visible': self.signature_visible,
            }
            
            # Prepare file
            files = {
                'document': (document_name, document_data, 'application/pdf')
            }
            
            # Make API request
            result = self._make_api_request('sign/document', method='POST', data=data, files=files)
            
            if result.get('success'):
                # Update statistics
                self.write({
                    'total_signatures': self.total_signatures + 1,
                    'successful_signatures': self.successful_signatures + 1,
                    'last_signature_date': fields.Datetime.now()
                })
                
                # Get signed document
                signed_data = base64.b64decode(result.get('signed_document'))
                
                _logger.info(f'Document signed successfully: {document_name}')
                
                return {
                    'success': True,
                    'message': 'Dokumen berhasil ditandatangani',
                    'signed_data': signed_data,
                    'request_id': result.get('request_id'),
                    'signature_id': result.get('signature_id'),
                    'certificate': result.get('certificate'),
                }
            else:
                # Update statistics
                self.write({
                    'total_signatures': self.total_signatures + 1,
                    'failed_signatures': self.failed_signatures + 1,
                })
                
                raise UserError(f'BSRE signing failed: {result.get("message")}')
                
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'Error signing document: {error_msg}')
            
            # Update statistics
            self.write({
                'total_signatures': self.total_signatures + 1,
                'failed_signatures': self.failed_signatures + 1,
            })
            
            return {
                'success': False,
                'message': f'Tanda tangan gagal: {error_msg}'
            }
    
    def verify_signature(self, document_data):
        """
        Verify digital signature pada dokumen
        
        Args:
            document_data (bytes): Binary data PDF
        
        Returns:
            dict: Verification result
        """
        self.ensure_one()
        
        try:
            # Prepare file
            files = {
                'document': ('document.pdf', document_data, 'application/pdf')
            }
            
            # Make API request
            result = self._make_api_request('verify/signature', method='POST', files=files)
            
            if result.get('success'):
                return {
                    'success': True,
                    'valid': result.get('valid'),
                    'signer': result.get('signer'),
                    'signature_date': result.get('signature_date'),
                    'certificate': result.get('certificate'),
                    'message': 'Verifikasi berhasil'
                }
            else:
                return {
                    'success': False,
                    'valid': False,
                    'message': result.get('message', 'Verifikasi gagal')
                }
                
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'Error verifying signature: {error_msg}')
            
            return {
                'success': False,
                'valid': False,
                'message': f'Verifikasi gagal: {error_msg}'
            }
    
    def action_view_statistics(self):
        """View signature statistics"""
        self.ensure_one()
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Statistik BSRE',
            'res_model': 'bsre.config',
            'res_id': self.id,
            'view_mode': 'form',
            'target': 'current',
        }

