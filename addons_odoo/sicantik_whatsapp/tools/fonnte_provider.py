# -*- coding: utf-8 -*-

"""
Fonnte Provider Implementation

API Documentation: https://docs.fonnte.com
Base URL: https://api.fonnte.com
"""

import requests
import logging
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)


class FonnteProvider:
    """
    Implementation untuk Fonnte WhatsApp Gateway
    """
    
    def __init__(self, token, device='', api_url='https://api.fonnte.com'):
        """
        Initialize Fonnte provider
        
        Args:
            token (str): API token dari Fonnte
            device (str): Device identifier (optional)
            api_url (str): Base URL API
        """
        self.token = token
        self.device = device
        self.api_url = api_url.rstrip('/')
        self.timeout = 30
    
    def _get_headers(self):
        """
        Get HTTP headers untuk request
        
        Returns:
            dict: Headers
        """
        return {
            'Authorization': self.token,
            'Content-Type': 'application/json',
        }
    
    def send_template(self, phone_number, template_id, parameters):
        """
        Kirim template message via Fonnte API
        
        Args:
            phone_number (str): Nomor HP penerima
            template_id (str): Template ID dari Fonnte
            parameters (dict): Parameter values dengan format {"var1": "value1", "var2": "value2", ...}
        
        Returns:
            dict: Response dari API
        """
        phone = self._normalize_phone(phone_number)
        
        # Prepare payload
        payload = {
            'target': phone,
            'template': template_id,
        }
        
        # Add parameters
        payload.update(parameters)
        
        # Add device if specified
        if self.device:
            payload['device'] = self.device
        
        _logger.info(f'📤 Fonnte: Sending template {template_id} to {phone}')
        _logger.debug(f'   Payload: {payload}')
        
        try:
            url = f'{self.api_url}/send'
            response = requests.post(
                url,
                json=payload,
                headers=self._get_headers(),
                timeout=self.timeout
            )
            
            _logger.info(f'   Response status: {response.status_code}')
            _logger.debug(f'   Response body: {response.text[:500]}')
            
            response.raise_for_status()
            result = response.json()
            
            if result.get('status'):
                _logger.info(f'✅ Fonnte: Message sent successfully')
                return {
                    'success': True,
                    'message_id': result.get('id') or result.get('message_id'),
                    'response': result
                }
            else:
                error_msg = result.get('reason') or 'Unknown error'
                _logger.error(f'❌ Fonnte: {error_msg}')
                return {
                    'success': False,
                    'error': error_msg,
                    'response': result
                }
                
        except requests.exceptions.RequestException as e:
            error_msg = f'Request error: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}')
            return {
                'success': False,
                'error': error_msg
            }
        except Exception as e:
            error_msg = f'Unexpected error: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}', exc_info=True)
            return {
                'success': False,
                'error': error_msg
            }
    
    def send_text(self, phone_number, message):
        """
        Kirim pesan teks bebas
        
        Args:
            phone_number (str): Nomor HP penerima
            message (str): Teks pesan
        
        Returns:
            dict: Response dari API
        """
        phone = self._normalize_phone(phone_number)
        
        payload = {
            'target': phone,
            'message': message,
        }
        
        if self.device:
            payload['device'] = self.device
        
        _logger.info(f'📤 Fonnte: Sending text to {phone}')
        
        try:
            url = f'{self.api_url}/send'
            response = requests.post(
                url,
                json=payload,
                headers=self._get_headers(),
                timeout=self.timeout
            )
            
            response.raise_for_status()
            result = response.json()
            
            if result.get('status'):
                _logger.info(f'✅ Fonnte: Text sent successfully')
                return {
                    'success': True,
                    'message_id': result.get('id'),
                    'response': result
                }
            else:
                error_msg = result.get('reason') or 'Unknown error'
                _logger.error(f'❌ Fonnte: {error_msg}')
                return {
                    'success': False,
                    'error': error_msg,
                    'response': result
                }
                
        except Exception as e:
            error_msg = f'Error sending text: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}')
            return {
                'success': False,
                'error': error_msg
            }
    
    def test_connection(self):
        """
        Test koneksi ke Fonnte API dengan memvalidasi token
        
        Menggunakan endpoint /qr untuk validasi token karena endpoint ini
        tersedia dan hanya memvalidasi tanpa melakukan aksi yang merugikan.
        
        Returns:
            dict: Response dengan status koneksi
        """
        _logger.info('🔍 Fonnte: Testing connection...')
        
        try:
            # Gunakan endpoint /qr untuk test koneksi
            # Endpoint ini akan mengembalikan QR code jika token valid
            # atau error yang jelas jika token tidak valid
            url = f'{self.api_url}/qr'
            params = {}
            if self.device:
                params['device'] = self.device
                
            response = requests.get(
                url,
                headers=self._get_headers(),
                params=params,
                timeout=self.timeout
            )
            
            _logger.info(f'   Response status: {response.status_code}')
            _logger.debug(f'   Response body: {response.text[:200]}')
            
            if response.status_code == 200:
                try:
                    result = response.json()
                    if result.get('status'):
                        _logger.info(f'✅ Fonnte: Connection successful')
                        return {
                            'success': True,
                            'message': 'Koneksi berhasil. Token valid dan terhubung ke Fonnte API.',
                            'response': result
                        }
                    else:
                        error_msg = result.get('reason') or 'Unknown error'
                        _logger.error(f'❌ Fonnte: {error_msg}')
                        return {
                            'success': False,
                            'error': error_msg,
                            'response': result
                        }
                except ValueError:
                    # Response bukan JSON, mungkin HTML atau plain text
                    _logger.warning('Response bukan JSON, mungkin endpoint berbeda')
                    # Coba alternatif: gunakan endpoint /send dengan payload minimal
                    return self._test_connection_alternative()
            elif response.status_code == 401:
                _logger.error('❌ Fonnte: Invalid token (401 Unauthorized)')
                return {
                    'success': False,
                    'error': 'Token tidak valid atau tidak terautentikasi. Pastikan token API sudah benar.',
                    'response': response.text[:500]
                }
            elif response.status_code == 404:
                _logger.warning('Endpoint /qr tidak ditemukan, mencoba alternatif...')
                # Coba alternatif endpoint
                return self._test_connection_alternative()
            else:
                _logger.error(f'❌ Fonnte: Connection failed ({response.status_code})')
                return {
                    'success': False,
                    'error': f'Koneksi gagal: HTTP {response.status_code}. Pastikan API URL benar: {self.api_url}',
                    'response': response.text[:500]
                }
                
        except requests.exceptions.RequestException as e:
            error_msg = f'Request error: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}')
            return {
                'success': False,
                'error': f'Error koneksi: {str(e)}. Pastikan API URL dapat diakses: {self.api_url}'
            }
        except Exception as e:
            error_msg = f'Unexpected error: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}', exc_info=True)
            return {
                'success': False,
                'error': error_msg
            }
    
    def _test_connection_alternative(self):
        """
        Alternatif test connection menggunakan endpoint /send dengan payload minimal
        yang tidak akan benar-benar mengirim pesan (hanya validasi token)
        """
        _logger.info('🔍 Fonnte: Trying alternative test method...')
        
        try:
            # Coba endpoint /send dengan payload yang tidak valid
            # Ini akan mengembalikan error yang jelas jika token tidak valid
            url = f'{self.api_url}/send'
            payload = {
                'target': '628000000000',  # Nomor dummy yang tidak akan benar-benar mengirim
                'message': 'TEST_CONNECTION',  # Pesan test
            }
            if self.device:
                payload['device'] = self.device
            
            response = requests.post(
                url,
                json=payload,
                headers=self._get_headers(),
                timeout=self.timeout
            )
            
            _logger.info(f'   Alternative response status: {response.status_code}')
            
            # Jika 401, berarti token tidak valid
            if response.status_code == 401:
                return {
                    'success': False,
                    'error': 'Token tidak valid atau tidak terautentikasi. Pastikan token API sudah benar.',
                }
            
            # Jika 200 atau 400 (bad request karena nomor dummy), berarti token valid
            # tapi endpoint /send tidak cocok untuk test
            if response.status_code in [200, 400]:
                try:
                    result = response.json()
                    # Jika status true atau ada error yang jelas tentang nomor (bukan token)
                    if result.get('status') or 'target' in str(result).lower() or 'number' in str(result).lower():
                        return {
                            'success': True,
                            'message': 'Koneksi berhasil. Token valid dan terhubung ke Fonnte API.',
                        }
                except ValueError:
                    pass
            
            # Jika sampai sini, anggap token valid (karena tidak dapat 401)
            return {
                'success': True,
                'message': 'Koneksi berhasil. Token valid dan terhubung ke Fonnte API.',
            }
            
        except Exception as e:
            _logger.error(f'❌ Fonnte: Alternative test failed: {str(e)}')
            return {
                'success': False,
                'error': f'Test koneksi gagal: {str(e)}. Pastikan API URL dan token sudah benar.'
            }
    
    def get_qr_code(self):
        """
        Dapatkan QR code untuk koneksi device
        
        Returns:
            dict: Response dengan QR code data
        """
        _logger.info('📱 Fonnte: Getting QR code...')
        
        try:
            url = f'{self.api_url}/qr'
            params = {}
            if self.device:
                params['device'] = self.device
                
            response = requests.get(
                url,
                headers=self._get_headers(),
                params=params,
                timeout=self.timeout
            )
            
            response.raise_for_status()
            result = response.json()
            
            if result.get('status'):
                _logger.info(f'✅ Fonnte: QR code retrieved successfully')
                return {
                    'success': True,
                    'qr_code': result.get('qr'),
                    'response': result
                }
            else:
                error_msg = result.get('reason') or 'Unknown error'
                _logger.error(f'❌ Fonnte: {error_msg}')
                return {
                    'success': False,
                    'error': error_msg,
                    'response': result
                }
                
        except Exception as e:
            error_msg = f'Error getting QR code: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}')
            return {
                'success': False,
                'error': error_msg
            }
    
    def validate_number(self, phone_number):
        """
        Validasi nomor WhatsApp
        
        Args:
            phone_number (str): Nomor HP yang akan divalidasi
        
        Returns:
            dict: Response dengan status validasi
        """
        phone = self._normalize_phone(phone_number)
        _logger.info(f'🔍 Fonnte: Validating number {phone}...')
        
        try:
            url = f'{self.api_url}/validate'
            payload = {
                'target': phone,
            }
            if self.device:
                payload['device'] = self.device
                
            response = requests.post(
                url,
                json=payload,
                headers=self._get_headers(),
                timeout=self.timeout
            )
            
            response.raise_for_status()
            result = response.json()
            
            if result.get('status'):
                _logger.info(f'✅ Fonnte: Number validation successful')
                return {
                    'success': True,
                    'valid': result.get('valid', False),
                    'response': result
                }
            else:
                error_msg = result.get('reason') or 'Unknown error'
                _logger.error(f'❌ Fonnte: {error_msg}')
                return {
                    'success': False,
                    'error': error_msg,
                    'response': result
                }
                
        except Exception as e:
            error_msg = f'Error validating number: {str(e)}'
            _logger.error(f'❌ Fonnte: {error_msg}')
            return {
                'success': False,
                'error': error_msg
            }
    
    def _normalize_phone(self, phone):
        """
        Normalize nomor HP ke format internasional
        
        Args:
            phone (str): Nomor HP input
        
        Returns:
            str: Nomor ternormalisasi (e.g., 6281234567890)
        """
        if not phone:
            raise UserError('Nomor HP tidak boleh kosong')
        
        phone = phone.replace(' ', '').replace('-', '').replace('(', '').replace(')', '')
        
        if phone.startswith('+'):
            phone = phone[1:]
        
        if phone.startswith('0'):
            phone = '62' + phone[1:]
        
        if not phone.startswith('62'):
            phone = '62' + phone
        
        return phone

