# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import requests
import base64
import json
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
        default='http://tte.karokab.go.id',
        help='Base URL untuk BSRE API'
    )
    username = fields.Char(
        string='Username',
        required=True,
        help='Username akun BSRE (untuk Basic Auth header)'
    )
    password = fields.Char(
        string='Password',
        required=True,
        help='Password akun BSRE (untuk Basic Auth header)'
    )
    
    # User Signing Credentials (untuk actual signing)
    signing_identifier_type = fields.Selection([
        ('nik', 'NIK'),
        ('email', 'Email'),
    ], string='Tipe Identifier', default='nik', required=True,
       help='Identifier untuk signing: NIK atau Email')
    
    signing_identifier = fields.Char(
        string='NIK/Email untuk Signing',
        required=True,
        help='NIK atau Email yang akan digunakan untuk sign dokumen'
    )
    
    use_otp = fields.Boolean(
        string='Gunakan OTP',
        default=False,
        help='Gunakan OTP instead of passphrase (belum diimplementasikan)'
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
    
    # Custom Signature Appearance
    signature_image = fields.Binary(
        string='Logo/Tanda Tangan',
        attachment=True,
        help='Upload logo atau gambar tanda tangan untuk ditampilkan di PDF. Format: PNG dengan transparansi. Jika kosong, akan menggunakan placeholder default.'
    )
    signature_image_filename = fields.Char(
        string='Nama File'
    )
    
    # Signature Size Preset
    signature_size = fields.Selection([
        ('small', 'Kecil (80x60)'),
        ('medium', 'Sedang (100x75)'),
        ('large', 'Besar (120x90)'),
        ('xlarge', 'Ekstra Besar (150x112)'),
        ('custom', 'Custom'),
    ], string='Ukuran Signature', default='medium',
       help='Preset ukuran signature atau pilih Custom untuk input manual')
    
    # Signature Dimensions (for custom size)
    signature_width = fields.Float(
        string='Lebar Signature (px)',
        default=100.0,
        help='Lebar signature field dalam pixel (aktif jika ukuran = Custom)'
    )
    signature_height = fields.Float(
        string='Tinggi Signature (px)',
        default=75.0,
        help='Tinggi signature field dalam pixel (aktif jika ukuran = Custom)'
    )
    
    # Custom Position Override
    use_custom_position = fields.Boolean(
        string='Gunakan Posisi Custom',
        default=False,
        help='Centang untuk menggunakan koordinat X,Y manual (override preset posisi)'
    )
    custom_position_x = fields.Float(
        string='Posisi X (px)',
        default=0.0,
        help='Koordinat X dari kiri PDF (0 = kiri). Hanya aktif jika "Gunakan Posisi Custom" dicentang.'
    )
    custom_position_y = fields.Float(
        string='Posisi Y (px)',
        default=0.0,
        help='Koordinat Y dari bawah PDF (0 = bawah). Hanya aktif jika "Gunakan Posisi Custom" dicentang.'
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
        Make API request to BSRE using Basic Authentication
        
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
        
        # Use Basic Authentication with username and password
        auth = (self.username, self.password)
        
        headers = {}
        
        try:
            _logger.info(f'BSRE API Request: {method} {url}')
            
            if method == 'POST':
                if files:
                    response = requests.post(url, auth=auth, data=data, files=files, timeout=self.api_timeout)
                else:
                    headers['Content-Type'] = 'application/json'
                    # Log request data (tanpa file base64 untuk tidak flood log)
                    debug_data = {k: v if k != 'file' else f'[{len(v)} files]' for k, v in (data or {}).items()}
                    _logger.info(f'Request data: {json.dumps(debug_data)}')
                    
                    response = requests.post(url, auth=auth, headers=headers, json=data, timeout=self.api_timeout)
            elif method == 'GET':
                response = requests.get(url, auth=auth, params=data, timeout=self.api_timeout)
            else:
                raise NotImplementedError(f'HTTP method {method} not implemented')
            
            _logger.info(f'BSRE API Response: {response.status_code}')
            
            # Log response body untuk debugging (truncate jika terlalu panjang)
            try:
                response_text = response.text[:1000] if len(response.text) > 1000 else response.text
                _logger.info(f'Response body: {response_text}')
            except:
                pass
            
            # Check status code before raise_for_status
            if response.status_code != 200:
                error_details = {
                    'status_code': response.status_code,
                    'url': url,
                    'method': method,
                    'response_text': response.text[:500] if response.text else 'No response body'
                }
                _logger.error(f'BSRE API Error: {json.dumps(error_details, indent=2)}')
                
                # IMPORTANT: BSRE sometimes returns 500 but with valid signed PDF in response body
                # Try to parse response as JSON first to check for 'file' key
                try:
                    error_json = response.json()
                    
                    # If response contains 'file' key with signed PDF, treat as success despite 500 error
                    if isinstance(error_json, dict) and 'file' in error_json and error_json.get('file'):
                        _logger.warning(f'BSRE returned {response.status_code} but response contains valid signed file. Treating as success.')
                        return error_json
                    
                    # Otherwise, it's a real error
                    error_msg = f"BSRE API Error {response.status_code}: {error_json.get('message', error_json)}"
                except:
                    error_msg = f"BSRE API Error {response.status_code}: {response.text[:200]}"
                
                raise UserError(error_msg)
            
            response.raise_for_status()
            
            # IMPORTANT: For FORMDATA signing (with files parameter), response is binary PDF
            # For JSON endpoints, response is JSON
            if files:
                # Signing with FORMDATA returns binary PDF directly
                _logger.info(f'FORMDATA signing successful, returning binary PDF (size: {len(response.content)} bytes)')
                # Return dict dengan content dan metadata untuk compatibility
                return {
                    'success': True,
                    'content': response.content,
                    'content_type': response.headers.get('Content-Type', 'application/pdf')
                }
            else:
                # JSON endpoints return JSON
                return response.json()
            
        except requests.exceptions.Timeout:
            error_msg = f'API request timeout after {self.api_timeout} seconds'
            _logger.error(error_msg)
            raise UserError(error_msg)
        
        except requests.exceptions.RequestException as e:
            error_msg = f'API request failed: {str(e)}'
            _logger.error(f'{error_msg}\nURL: {url}\nMethod: {method}')
            raise UserError(error_msg)
        
        except Exception as e:
            error_msg = f'Unexpected error: {str(e)}'
            _logger.error(error_msg)
            raise UserError(error_msg)
    
    def action_test_connection(self):
        """Test koneksi ke BSRE API - Simplified version"""
        self.ensure_one()
        
        # TODO: Update dengan endpoint BSRE yang benar setelah dapat dokumentasi
        # Untuk sementara, hanya validasi konfigurasi
        
        if not self.api_url or not self.username or not self.password:
            raise UserError('Konfigurasi tidak lengkap. Pastikan URL, Username, dan Password sudah diisi.')
        
        self.write({
            'connection_status': 'disconnected',
            'last_error': 'Test connection belum diimplementasikan. Silakan langsung test dengan sign dokumen.'
        })
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Perhatian',
                'message': 'Konfigurasi tersimpan. Untuk test koneksi, silakan langsung sign dokumen. Endpoint test belum tersedia di API BSRE.',
                'type': 'warning',
                'sticky': False,
            }
        }
    
    def _process_signature_image(self):
        """
        Process and optimize signature image for BSRE API
        - Resize to max 100x75 pixel
        - Convert to simple PNG format
        - Compress to reduce base64 size
        
        Returns:
            str: Base64 encoded optimized image, or None if no image
        """
        if not self.signature_image:
            return None
        
        try:
            from PIL import Image
            import io
            
            # Decode uploaded image
            image_data = base64.b64decode(self.signature_image)
            image = Image.open(io.BytesIO(image_data))
            
            # Convert to RGB (remove alpha channel if exists)
            if image.mode in ('RGBA', 'LA', 'P'):
                # Create white background
                background = Image.new('RGB', image.size, (255, 255, 255))
                if image.mode == 'P':
                    image = image.convert('RGBA')
                background.paste(image, mask=image.split()[-1] if image.mode in ('RGBA', 'LA') else None)
                image = background
            elif image.mode != 'RGB':
                image = image.convert('RGB')
            
            # Resize to max 100x75 maintaining aspect ratio
            max_width, max_height = 100, 75
            image.thumbnail((max_width, max_height), Image.Resampling.LANCZOS)
            
            # Save as optimized PNG
            output = io.BytesIO()
            image.save(output, format='PNG', optimize=True)
            optimized_data = output.getvalue()
            
            # Convert to base64
            optimized_base64 = base64.b64encode(optimized_data).decode('utf-8')
            
            _logger.info(f'Signature image optimized: {len(self.signature_image)} -> {len(optimized_base64)} chars')
            
            return optimized_base64
            
        except Exception as e:
            _logger.warning(f'Failed to process signature image: {str(e)}. Will use placeholder.')
            return None
    
    def sign_document(self, document_data, document_name, passphrase=None):
        """
        Sign document dengan BSRE TTE API v2
        
        Args:
            document_data (bytes): Binary data PDF
            document_name (str): Nama dokumen
            passphrase (str): Passphrase untuk signing (dari user input)
        
        Returns:
            dict: Result with success status, signed data, and metadata
        """
        self.ensure_one()
        
        try:
            # Validate passphrase
            if self.use_otp:
                raise UserError('OTP signing belum diimplementasikan. Silakan gunakan Passphrase.')
            
            if not passphrase:
                raise UserError('Passphrase wajib diisi untuk menandatangani dokumen.')
            
            # BSRE API menggunakan multipart/form-data, bukan JSON!
            # Sesuai spec dari Postman collection
            
            _logger.info(f'Signing document with BSRE API v2: {document_name}')
            
            # Prepare form data (text fields)
            form_data = {
                'nik': self.signing_identifier if self.signing_identifier_type == 'nik' else '',
                'email': self.signing_identifier if self.signing_identifier_type == 'email' else '',
                'passphrase': passphrase,
                'tampilan': 'visible' if self.signature_visible else 'invisible',
            }
            
            # Prepare files dict for multipart upload
            files_dict = {
                # PDF file sebagai binary upload
                'file': (document_name, document_data, 'application/pdf'),
            }
            
            # Add signature visualization parameters jika visible
            if self.signature_visible:
                # Get all signature parameters
                sig_width = self._get_signature_width()
                sig_height = self._get_signature_height()
                pos_x = self._get_position_x()
                pos_y = self._get_position_y()
                
                form_data['image'] = 'true' if self.signature_image else 'false'
                form_data['page'] = '1'  # Always page 1 for now
                form_data['xAxis'] = str(int(pos_x))
                form_data['yAxis'] = str(int(pos_y))
                form_data['width'] = str(int(sig_width))
                form_data['height'] = str(int(sig_height))
                
                # LOG DETAIL untuk debug
                _logger.info('═' * 60)
                _logger.info('📐 SIGNATURE PARAMETERS DETAIL:')
                _logger.info(f'   Position Preset: {self.signature_position}')
                _logger.info(f'   Size Preset: {self.signature_size}')
                _logger.info(f'   Custom Position: {self.use_custom_position}')
                _logger.info(f'   Width: {sig_width} px (sent as: {int(sig_width)})')
                _logger.info(f'   Height: {sig_height} px (sent as: {int(sig_height)})')
                _logger.info(f'   X Position: {pos_x} px (sent as: {int(pos_x)})')
                _logger.info(f'   Y Position: {pos_y} px (sent as: {int(pos_y)})')
                _logger.info('═' * 60)
                
                # Add signature image file jika ada
                if self.signature_image:
                    # Decode base64 to binary
                    image_binary = base64.b64decode(self.signature_image)
                    files_dict['imageTTD'] = ('signature.png', image_binary, 'image/png')
                    _logger.info(f'✅ Using uploaded signature image: size={len(image_binary)} bytes, base64_length={len(self.signature_image)} chars')
                    _logger.info(f'✅ imageTTD will be uploaded as binary file')
                else:
                    # Jika tidak ada upload, BSRE akan pakai QR Code (perlu linkQR)
                    form_data['linkQR'] = 'https://tte.karokab.go.id/verify'
                    _logger.info('⚠️ No signature image uploaded - BSRE will use QR Code only')
                    _logger.info('⚠️ Please upload signature image in BSRE config if you want custom logo')
            
            _logger.info(f'Form data (without passphrase): {", ".join([f"{k}={v}" for k, v in form_data.items() if k != "passphrase"])}')
            _logger.info(f'Files to upload: {", ".join(files_dict.keys())}')
            
            # Make API request to BSRE dengan FORMDATA
            # NOTE: Endpoint untuk FORMDATA adalah /api/sign/pdf (bukan /api/v2/sign/pdf)
            result = self._make_api_request('api/sign/pdf', method='POST', data=form_data, files=files_dict)
            
            # Handle response based on format
            # FORMDATA endpoint returns: {'success': True, 'content': binary_pdf, 'content_type': 'application/pdf'}
            # JSON endpoint returns: {'time': 1099, 'file': ['base64_signed_pdf']}
            
            if result and isinstance(result, dict):
                # Check for FORMDATA response (binary PDF)
                if 'content' in result and result.get('success'):
                    signed_data = result['content']
                    _logger.info(f'Document signed successfully with FORMDATA: {document_name} (PDF size: {len(signed_data)} bytes)')
                    
                    # Update statistics
                    self.write({
                        'total_signatures': self.total_signatures + 1,
                        'successful_signatures': self.successful_signatures + 1,
                        'last_signature_date': fields.Datetime.now()
                    })
                    
                    return {
                        'success': True,
                        'message': 'Dokumen berhasil ditandatangani dengan BSRE',
                        'signed_data': signed_data,
                    }
                
                # Check for JSON response (base64 PDF in 'file' key)
                elif 'file' in result:
                    file_list = result.get('file', [])
                    processing_time = result.get('time', 0)
                    
                    if file_list and isinstance(file_list, list) and len(file_list) > 0:
                        # Get first signed document
                        signed_base64 = file_list[0]
                        signed_data = base64.b64decode(signed_base64)
                        
                        # Update statistics
                        self.write({
                            'total_signatures': self.total_signatures + 1,
                            'successful_signatures': self.successful_signatures + 1,
                            'last_signature_date': fields.Datetime.now()
                        })
                        
                        _logger.info(f'Document signed successfully with JSON: {document_name} (processing time: {processing_time}ms)')
                        
                        return {
                            'success': True,
                            'message': 'Dokumen berhasil ditandatangani dengan BSRE',
                            'signed_data': signed_data,
                        }
                    else:
                        raise UserError(f'BSRE API response tidak mengandung file yang valid: {result}')
                else:
                    raise UserError(f'BSRE API response tidak valid (expected "content" or "file" key): {result}')
            else:
                raise UserError(f'BSRE API response bukan dict yang valid: {result}')
                
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
    
    def _get_signature_width(self):
        """Get signature width based on size preset or custom value"""
        self.ensure_one()
        
        # Size presets (width x height)
        SIZE_PRESETS = {
            'small': 80,
            'medium': 100,
            'large': 120,
            'xlarge': 150,
        }
        
        if self.signature_size == 'custom':
            return self.signature_width
        else:
            return SIZE_PRESETS.get(self.signature_size, 100)
    
    def _get_signature_height(self):
        """Get signature height based on size preset or custom value"""
        self.ensure_one()
        
        # Size presets (width x height)
        SIZE_PRESETS = {
            'small': 60,
            'medium': 75,
            'large': 90,
            'xlarge': 112,
        }
        
        if self.signature_size == 'custom':
            return self.signature_height
        else:
            return SIZE_PRESETS.get(self.signature_size, 75)
    
    def _get_position_x(self):
        """
        Get X coordinate based on signature position or custom override
        
        Koordinat sistem BSRE (CONFIRMED BY TESTING):
        - xAxis=0 = KIRI (standard)
        - xAxis bertambah ke KANAN (standard)
        - Coordinates represent BOTTOM-LEFT corner of signature box
        
        CRITICAL FINDING: BSRE API does NOT auto-center the signature!
        The coordinates ALWAYS represent the bottom-left corner.
        We MUST calculate the position ourselves based on signature size.
        """
        self.ensure_one()
        
        # Use custom position if enabled
        if self.use_custom_position:
            return self.custom_position_x
        
        # Get signature width untuk perhitungan posisi
        sig_width = self._get_signature_width()
        
        # Koordinat BSRE X-axis (CALCULATED using same formula as Y)
        # Base coordinates (for centering the signature properly):
        # - LEFT edge = 10
        # - CENTER point = 505
        # - RIGHT edge = 1000 = (505 × 2) - 10
        
        # Calculate BOTTOM-LEFT corner position for each preset
        # For corner positions: direct positioning
        # For center positions: center_point - (sig_width / 2)
        
        POSITIONS_X = {
            'bottom_left': 10,                                   # Corner: direct
            'top_left': 10,                                      # Corner: direct
            'bottom_right': 1000 - sig_width,                    # Corner: right edge - width
            'top_right': 1000 - sig_width,                       # Corner: right edge - width
            'center': 505 - (sig_width / 2),                     # Center: center point - half width
        }
        
        position = POSITIONS_X.get(self.signature_position, 10)
        _logger.info(f'🎯 X Position: preset={self.signature_position}, sig_width={sig_width}, result={position}')
        return position
    
    def _get_position_y(self):
        """
        Get Y coordinate based on signature position or custom override
        
        Koordinat sistem BSRE (CONFIRMED BY TESTING):
        - yAxis=0 = BAWAH (not top!)
        - yAxis bertambah ke ATAS (not down!)
        - Y axis is FLIPPED from standard PDF
        - Coordinates represent BOTTOM-LEFT corner of signature box
        
        CRITICAL FINDING: BSRE API does NOT auto-center the signature!
        The coordinates ALWAYS represent the bottom-left corner.
        We MUST calculate the position ourselves based on signature size.
        """
        self.ensure_one()
        
        # Use custom position if enabled
        if self.use_custom_position:
            return self.custom_position_y
        
        # Get signature height untuk perhitungan posisi
        sig_height = self._get_signature_height()
        
        # Koordinat BSRE Y-axis (CONFIRMED BY USER!)
        # Base coordinates (for centering the signature properly):
        # - BOTTOM edge = 10
        # - CENTER point = 772
        # - TOP edge = 1534 = (772 × 2) - 10
        
        # Calculate BOTTOM-LEFT corner position for each preset
        # For bottom positions: direct positioning
        # For top positions: top edge - height
        # For center positions: center_point - (sig_height / 2)
        
        POSITIONS_Y = {
            'bottom_left': 10,                                   # Bottom: direct
            'bottom_right': 10,                                  # Bottom: direct
            'top_left': 1534 - sig_height,                       # Top: top edge - height
            'top_right': 1534 - sig_height,                      # Top: top edge - height
            'center': 772 - (sig_height / 2),                    # Center: center point - half height
        }
        
        position = POSITIONS_Y.get(self.signature_position, 10)
        _logger.info(f'🎯 Y Position: preset={self.signature_position}, sig_height={sig_height}, result={position}')
        return position
    
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

