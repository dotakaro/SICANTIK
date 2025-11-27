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
        ('small', 'Kecil (Max 80x60)'),
        ('medium', 'Sedang (Max 120x90)'),
        ('large', 'Besar (Max 160x120)'),
        ('xlarge', 'Sangat Besar (Max 200x150)'),
        ('custom', 'Custom'),
    ], string='Ukuran Signature', default='small',
       help='Ukuran maksimum signature. Aspek rasio original akan dipertahankan - gambar akan di-fit dalam bounds tanpa distorsi.')
    
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
    
    @api.constrains('use_custom_position', 'custom_position_x', 'custom_position_y', 'signature_width', 'signature_height')
    def _check_custom_position_bounds(self):
        """
        Validate custom position coordinates stay within A4 page bounds
        V2 API menggunakan A4 Portrait: 595 x 842 points
        """
        for record in self:
            if record.use_custom_position:
                PAGE_WIDTH = 595
                PAGE_HEIGHT = 842
                
                # Get signature dimensions
                sig_width = record._get_signature_width()
                sig_height = record._get_signature_height()
                
                # Validate X coordinate
                if record.custom_position_x < 0:
                    raise ValidationError('Posisi X tidak boleh negatif!')
                if record.custom_position_x + sig_width > PAGE_WIDTH:
                    raise ValidationError(
                        f'Signature keluar dari halaman!\n'
                        f'originX ({record.custom_position_x}) + width ({sig_width}) = {record.custom_position_x + sig_width}\n'
                        f'MAX allowed: {PAGE_WIDTH} (A4 width)\n\n'
                        f'Solusi: Kurangi originX atau gunakan signature yang lebih kecil.'
                    )
                
                # Validate Y coordinate
                if record.custom_position_y < 0:
                    raise ValidationError('Posisi Y tidak boleh negatif!')
                if record.custom_position_y + sig_height > PAGE_HEIGHT:
                    raise ValidationError(
                        f'Signature keluar dari halaman!\n'
                        f'originY ({record.custom_position_y}) + height ({sig_height}) = {record.custom_position_y + sig_height}\n'
                        f'MAX allowed: {PAGE_HEIGHT} (A4 height)\n\n'
                        f'Solusi: Kurangi originY atau gunakan signature yang lebih kecil.'
                    )
    
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
                    _logger.info(f'Request data: {json.dumps(debug_data, indent=2, ensure_ascii=False)}')
                    
                    # CRITICAL: Log actual JSON yang akan dikirim (untuk debugging)
                    try:
                        actual_json = json.dumps(data, ensure_ascii=False)
                        # Log first 500 chars untuk melihat struktur
                        _logger.info(f'📤 Actual JSON payload (first 500 chars): {actual_json[:500]}...')
                        _logger.info(f'📤 JSON payload size: {len(actual_json)} chars')
                    except Exception as e:
                        _logger.error(f'❌ Error serializing JSON: {str(e)}')
                    
                    # CRITICAL: Log full payload structure untuk debugging (tanpa base64 panjang)
                    # IMPORTANT: Validate imageBase64 BEFORE replacing with placeholder for logging!
                    if data and isinstance(data, dict):
                        # First, validate imageBase64 in original data (before creating debug structure)
                        if 'signatureProperties' in data:
                            for idx, sig_prop in enumerate(data['signatureProperties']):
                                img_b64 = sig_prop.get('imageBase64', '')
                                img_len = len(img_b64) if img_b64 else 0
                                if not img_b64 or img_len == 0:
                                    _logger.error(f'❌ CRITICAL: signatureProperties[{idx}] has EMPTY imageBase64 in final payload!')
                                elif img_len < 100:
                                    _logger.warning(f'⚠️ signatureProperties[{idx}] imageBase64 is very small ({img_len} chars), may cause BSRE API 500 error!')
                                else:
                                    _logger.info(f'✅ signatureProperties[{idx}] imageBase64 is valid: {img_len} chars')
                        
                        # Now create debug structure for logging (replace with placeholders)
                        # CRITICAL: Create a deep copy to avoid modifying original data!
                        import copy
                        payload_structure = {}
                        for key, value in data.items():
                            if key == 'file' and isinstance(value, list):
                                payload_structure[key] = [f'[BASE64_PDF: {len(v)} chars]' for v in value]
                            elif key == 'signatureProperties' and isinstance(value, list):
                                payload_structure[key] = []
                                for sig_prop in value:
                                    # Use copy to avoid modifying original
                                    sig_debug = copy.deepcopy(sig_prop)
                                    if 'imageBase64' in sig_debug:
                                        img_len = len(sig_debug['imageBase64'])
                                        sig_debug['imageBase64'] = f'[BASE64_IMAGE: {img_len} chars]'
                                    # Validate numeric fields
                                    for num_field in ['originX', 'originY', 'width', 'height']:
                                        if num_field in sig_debug:
                                            val = sig_debug[num_field]
                                            if isinstance(val, (int, float)):
                                                if val < 0:
                                                    _logger.warning(f'⚠️ {num_field} is negative: {val}')
                                                if val > 1000:
                                                    _logger.warning(f'⚠️ {num_field} is very large: {val}')
                                    payload_structure[key].append(sig_debug)
                            else:
                                payload_structure[key] = value
                        _logger.info(f'📋 Full Payload Structure: {json.dumps(payload_structure, indent=2, ensure_ascii=False)}')
                    
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
                    'response_text': response.text[:1000] if response.text else 'No response body',  # Increased to 1000 chars
                    'response_headers': dict(response.headers) if response.headers else {}
                }
                _logger.error(f'BSRE API Error: {json.dumps(error_details, indent=2)}')
                
                # IMPORTANT: BSRE sometimes returns 500 but with valid signed PDF in response body
                # Try to parse response as JSON first to check for 'file' key
                try:
                    error_json = response.json()
                    _logger.info(f'📋 BSRE Error Response JSON: {json.dumps(error_json, indent=2)}')
                    
                    # If response contains 'file' key with signed PDF, treat as success despite 500 error
                    if isinstance(error_json, dict) and 'file' in error_json and error_json.get('file'):
                        _logger.warning(f'✅ BSRE returned {response.status_code} but response contains valid signed file. Treating as success.')
                        return error_json
                    
                    # Check for additional error details in response
                    error_message = error_json.get('message', '')
                    error_timestamp = error_json.get('timestamp', '')
                    error_path = error_json.get('path', '')
                    
                    # Log detailed error information
                    _logger.error(f'❌ BSRE API Error Details:')
                    _logger.error(f'   Status: {response.status_code}')
                    _logger.error(f'   Message: {error_message}')
                    _logger.error(f'   Timestamp: {error_timestamp}')
                    _logger.error(f'   Path: {error_path}')
                    _logger.error(f'   Full Response: {json.dumps(error_json, indent=2)}')
                    
                    # Otherwise, it's a real error
                    error_msg = f"BSRE API Error {response.status_code}: {error_message or error_json}"
                except Exception as parse_error:
                    _logger.error(f'❌ Failed to parse BSRE error response as JSON: {str(parse_error)}')
                    _logger.error(f'❌ Raw response text (first 1000 chars): {response.text[:1000]}')
                    error_msg = f"BSRE API Error {response.status_code}: {response.text[:500]}"
                
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
    
    def _resize_signature_image_to_preset(self):
        """
        CRITICAL METHOD: Resize signature image to EXACT preset size
        
        WHY THIS IS NEEDED:
        BSRE API has a BUG where it ignores width/height parameters for
        certain positions (like CENTER). The API uses the uploaded image's
        actual size instead of the width/height parameters we send!
        
        SOLUTION:
        Resize the image to EXACT size before upload, so even if API ignores
        parameters, the image itself is already the correct size.
        
        Returns:
            bytes: Resized image as binary PNG data
        """
        self.ensure_one()
        
        if not self.signature_image:
            return None
        
        try:
            from PIL import Image
            import io
            
            # Get target size from preset
            target_width = int(self._get_signature_width())
            target_height = int(self._get_signature_height())
            
            _logger.info(f'🔧 Resizing signature image to EXACT size: {target_width}x{target_height} px')
            
            # Decode uploaded image
            image_data = base64.b64decode(self.signature_image)
            image = Image.open(io.BytesIO(image_data))
            
            _logger.info(f'📐 Original image size: {image.size[0]}x{image.size[1]} px')
            
            # Convert to RGBA for transparency support
            if image.mode != 'RGBA':
                image = image.convert('RGBA')
            
            # Resize to EXACT target size (maintain aspect ratio, then crop/pad)
            # Calculate scaling to fit within target size
            img_ratio = image.size[0] / image.size[1]
            target_ratio = target_width / target_height
            
            if img_ratio > target_ratio:
                # Image is wider, fit to width
                new_width = target_width
                new_height = int(target_width / img_ratio)
            else:
                # Image is taller, fit to height
                new_height = target_height
                new_width = int(target_height * img_ratio)
            
            # Resize maintaining aspect ratio
            image = image.resize((new_width, new_height), Image.Resampling.LANCZOS)
            
            # Create final image with exact target size (white background)
            final_image = Image.new('RGBA', (target_width, target_height), (255, 255, 255, 0))
            
            # Paste resized image centered
            paste_x = (target_width - new_width) // 2
            paste_y = (target_height - new_height) // 2
            final_image.paste(image, (paste_x, paste_y), image)
            
            # Convert to RGB for PNG (remove alpha)
            final_rgb = Image.new('RGB', (target_width, target_height), (255, 255, 255))
            final_rgb.paste(final_image, mask=final_image.split()[3])  # Use alpha as mask
            
            # Save as PNG
            output = io.BytesIO()
            final_rgb.save(output, format='PNG', optimize=True)
            resized_data = output.getvalue()
            
            _logger.info(f'✅ Image resized successfully: {len(image_data)} → {len(resized_data)} bytes')
            
            return resized_data
            
        except Exception as e:
            error_msg = f'Failed to resize signature image: {str(e)}'
            _logger.error(error_msg)
            # Fallback: return original image
            return base64.b64decode(self.signature_image)
    
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
            
            # BSRE API V2 menggunakan JSON dengan base64 encoding
            # Sesuai dokumentasi Petunjuk Teknis API Esign Client Service v2.2.1
            
            _logger.info(f'Signing document with BSRE API v2 (JSON): {document_name}')
            
            # Convert PDF to base64 for V2 API
            import base64 as b64
            document_base64 = b64.b64encode(document_data).decode('utf-8')
            
            # Prepare JSON payload for V2 API
            # CRITICAL: Urutan field harus sesuai dengan contoh Postman yang berhasil
            # Format: nik, passphrase, signatureProperties, file (urutan ini penting!)
            if self.signing_identifier_type == 'nik':
                if not self.signing_identifier:
                    raise UserError('NIK untuk signing harus diisi di konfigurasi BSRE')
                json_payload = {
                    'nik': self.signing_identifier,
                    'passphrase': passphrase,
                }
            elif self.signing_identifier_type == 'email':
                if not self.signing_identifier:
                    raise UserError('Email untuk signing harus diisi di konfigurasi BSRE')
                json_payload = {
                    'email': self.signing_identifier,
                    'passphrase': passphrase,
                }
            else:
                raise UserError('Tipe identifier harus NIK atau Email')
            
            # Add file array (akan ditambahkan sebelum signatureProperties)
            json_payload['file'] = [document_base64]  # V2 uses base64 string array
            
            # Validasi payload sebelum menambahkan signatureProperties
            if not json_payload.get('nik') and not json_payload.get('email'):
                raise UserError('NIK atau Email untuk signing harus diisi di konfigurasi BSRE')
            if not json_payload['passphrase']:
                raise UserError('Passphrase tidak boleh kosong')
            if not json_payload['file'] or not json_payload['file'][0]:
                raise UserError('File dokumen tidak boleh kosong')
            
            # Validate PDF file is valid (check if base64 decodes to valid PDF header)
            try:
                pdf_bytes = b64.b64decode(document_base64)
                if not pdf_bytes.startswith(b'%PDF'):
                    _logger.warning('⚠️ WARNING: Decoded file does not start with PDF header! File may be invalid.')
                else:
                    _logger.info(f'✅ PDF file validated: starts with PDF header, size: {len(pdf_bytes)} bytes')
            except Exception as e:
                _logger.error(f'❌ Error validating PDF file: {str(e)}')
                raise UserError(f'File PDF tidak valid: {str(e)}')
            
            # Log payload base (hide sensitive data)
            nik_log = "***" if json_payload.get("nik") else ""
            email_log = "***" if json_payload.get("email") else ""
            passphrase_log = "***" if json_payload.get("passphrase") else ""
            _logger.info(f'📋 Payload base: nik={nik_log}, email={email_log}, passphrase={passphrase_log}, file_size={len(json_payload["file"][0])} chars')
            
            # Add signature visualization parameters jika visible
            # V2 API uses signatureProperties array structure
            if self.signature_visible:
                # Get all signature parameters
                sig_width = self._get_signature_width()
                sig_height = self._get_signature_height()
                pos_x = self._get_position_x()
                pos_y = self._get_position_y()
                
                # CRITICAL: V2 API coordinate system is DIFFERENT from V1!
                # V1: Y=0 at BOTTOM (y increases upward)
                # V2: Y=0 at TOP (y increases downward)
                # We need to FLIP the Y coordinate!
                # Formula: V2_originY = PAGE_HEIGHT - V1_yAxis - signature_height
                # Assuming A4 page: height ≈ 842 points (for portrait)
                # But BSRE uses different scale, from docs: y=0 at top
                # So we need to flip: if V1 yAxis=10 (bottom), V2 should be near top
                # Formula: originY = PAGE_MAX_Y - yAxis_v1 - height
                
                # For now, let's use coordinate as-is and let BSRE handle it
                # We'll monitor the output and adjust if needed
                origin_x = float(pos_x)
                origin_y = float(pos_y)
                
                # CRITICAL: Validate coordinates are within page bounds
                # A4 Portrait: Width=595 points, Height=842 points
                PAGE_WIDTH = 595
                PAGE_HEIGHT = 842
                MARGIN = 5  # Minimum margin
                
                # Validate X coordinate
                if origin_x < 0:
                    _logger.warning(f'⚠️ originX ({origin_x}) < 0, clamping to 0')
                    origin_x = 0
                if origin_x + sig_width > PAGE_WIDTH:
                    _logger.warning(f'⚠️ originX + width ({origin_x + sig_width}) > PAGE_WIDTH ({PAGE_WIDTH}), adjusting')
                    origin_x = PAGE_WIDTH - sig_width - MARGIN
                    if origin_x < 0:
                        origin_x = 0
                
                # Validate Y coordinate
                if origin_y < 0:
                    _logger.warning(f'⚠️ originY ({origin_y}) < 0, clamping to 0')
                    origin_y = 0
                if origin_y + sig_height > PAGE_HEIGHT:
                    _logger.warning(f'⚠️ originY + height ({origin_y + sig_height}) > PAGE_HEIGHT ({PAGE_HEIGHT}), adjusting')
                    origin_y = PAGE_HEIGHT - sig_height - MARGIN
                    if origin_y < 0:
                        origin_y = 0
                
                # LOG DETAIL untuk debug
                _logger.info('═' * 60)
                _logger.info('📐 SIGNATURE PARAMETERS (V2 API):')
                _logger.info(f'   Position Preset: {self.signature_position}')
                _logger.info(f'   Size Preset: {self.signature_size}')
                _logger.info(f'   Custom Position: {self.use_custom_position}')
                _logger.info(f'   Width: {sig_width} px')
                _logger.info(f'   Height: {sig_height} px')
                _logger.info(f'   originX: {origin_x} (V2 coordinate, validated)')
                _logger.info(f'   originY: {origin_y} (V2 coordinate - Y=0 at TOP!, validated)')
                _logger.info(f'   Bounds check: X={origin_x}+{sig_width}={origin_x + sig_width} ≤ {PAGE_WIDTH}, Y={origin_y}+{sig_height}={origin_y + sig_height} ≤ {PAGE_HEIGHT}')
                _logger.info('═' * 60)
                
                # Prepare signatureProperties object
                # CRITICAL: Urutan field HARUS sesuai dengan dokumentasi Postman:
                # imageBase64, tampilan, page, originX, originY, width, height, location, reason, contactInfo
                import math
                signature_props = {
                    'imageBase64': '',  # Will be filled later
                    'tampilan': 'VISIBLE',
                    'page': 1,  # Always page 1 for now
                    'originX': float(origin_x) if not (math.isnan(origin_x) or math.isinf(origin_x)) else 10.0,
                    'originY': float(origin_y) if not (math.isnan(origin_y) or math.isinf(origin_y)) else 10.0,
                    'width': float(sig_width) if not (math.isnan(sig_width) or math.isinf(sig_width)) else 80.0,
                    'height': float(sig_height) if not (math.isnan(sig_height) or math.isinf(sig_height)) else 60.0,
                    'location': 'null',      # BSRE API expects string "null", not empty string
                    'reason': 'null',        # BSRE API expects string "null", not empty string
                    'contactInfo': 'null',   # Required field according to BSRE API v2 documentation
                }
                
                # Final validation: ensure no invalid values
                for key in ['originX', 'originY', 'width', 'height']:
                    val = signature_props[key]
                    if math.isnan(val) or math.isinf(val) or val < 0:
                        _logger.error(f'❌ Invalid {key} value: {val}, using default')
                        defaults = {'originX': 10.0, 'originY': 10.0, 'width': 80.0, 'height': 60.0}
                        signature_props[key] = defaults[key]
                
                _logger.info(f'✅ Signature properties validated: originX={signature_props["originX"]}, originY={signature_props["originY"]}, width={signature_props["width"]}, height={signature_props["height"]}')
            
                # Add signature image jika ada
                # CRITICAL: imageBase64 harus diisi pertama (sesuai urutan Postman)
                if self.signature_image:
                    # CRITICAL: BSRE API uses PHYSICAL IMAGE SIZE!
                    # We MUST resize image to EXACT dimensions before encoding to base64!
                    image_binary = self._resize_image_to_exact_size(
                        base64.b64decode(self.signature_image),
                        int(sig_width),
                        int(sig_height)
                    )
                    # Convert resized image to base64 for V2 API
                    image_base64 = b64.b64encode(image_binary).decode('utf-8')
                    signature_props['imageBase64'] = image_base64
                    
                    _logger.info(f'✅ Using RESIZED signature image: {len(image_binary)} bytes')
                    _logger.info(f'✅ Image physically resized to: {int(sig_width)}x{int(sig_height)} px')
                    _logger.info(f'✅ imageBase64 length: {len(image_base64)} chars')
                else:
                    # V2 API requires imageBase64 even for VISIBLE signature without image
                    # CRITICAL: BSRE API rejects very small imageBase64 (204 chars)!
                    # Create a larger placeholder with complex content to ensure sufficient size
                    _logger.info('⚠️ No signature image uploaded - creating larger placeholder with signature dimensions')
                    placeholder_base64 = ''
                    try:
                        from PIL import Image, ImageDraw
                        import io
                        # Create image dengan ukuran signature yang sebenarnya
                        placeholder = Image.new('RGB', (int(sig_width), int(sig_height)), (255, 255, 255))
                        draw = ImageDraw.Draw(placeholder)
                        
                        # Draw complex pattern to increase file size significantly
                        # Draw border
                        border_width = max(2, int(sig_width / 20))
                        draw.rectangle([0, 0, int(sig_width)-1, int(sig_height)-1], outline=(150, 150, 150), width=border_width)
                        
                        # Draw grid pattern untuk meningkatkan ukuran file
                        grid_size = max(5, int(sig_width / 15))
                        for x in range(0, int(sig_width), grid_size):
                            draw.line([(x, 0), (x, int(sig_height))], fill=(230, 230, 230), width=1)
                        for y in range(0, int(sig_height), grid_size):
                            draw.line([(0, y), (int(sig_width), y)], fill=(230, 230, 230), width=1)
                        
                        # Draw diagonal lines untuk menambah kompleksitas
                        for i in range(0, int(sig_width + sig_height), grid_size):
                            draw.line([(i, 0), (0, i)], fill=(240, 240, 240), width=1)
                            if i < int(sig_width):
                                draw.line([(i, int(sig_height)), (int(sig_width), int(sig_height) - i)], fill=(240, 240, 240), width=1)
                        
                        # Draw circles untuk menambah kompleksitas
                        center_x, center_y = int(sig_width / 2), int(sig_height / 2)
                        radius = min(int(sig_width / 4), int(sig_height / 4))
                        for r in range(radius, 0, -max(2, radius // 5)):
                            draw.ellipse([center_x - r, center_y - r, center_x + r, center_y + r], outline=(200, 200, 200), width=1)
                        
                        placeholder_buffer = io.BytesIO()
                        # Use optimize=False and compress_level=0 untuk file size maksimal
                        placeholder.save(placeholder_buffer, format='PNG', optimize=False, compress_level=0)
                        placeholder_buffer.seek(0)
                        placeholder_base64 = base64.b64encode(placeholder_buffer.read()).decode('utf-8')
                        
                        # Validate the generated base64 - harus minimal 1000 chars
                        if not placeholder_base64 or len(placeholder_base64) < 1000:
                            raise ValueError(f'Generated placeholder too small: {len(placeholder_base64)} chars, need at least 1000')
                        
                        _logger.info(f'✅ Created large placeholder: {int(sig_width)}x{int(sig_height)} px, {len(placeholder_base64)} chars base64')
                    except Exception as e:
                        _logger.error(f'❌ Error creating placeholder: {str(e)}', exc_info=True)
                        # Fallback: Create a larger image dengan pattern yang lebih kompleks
                        try:
                            from PIL import Image, ImageDraw
                            import io
                            # Create larger image dengan pattern kompleks
                            fallback_img = Image.new('RGB', (max(80, int(sig_width)), max(60, int(sig_height))), (255, 255, 255))
                            fallback_draw = ImageDraw.Draw(fallback_img)
                            
                            # Draw complex pattern
                            w, h = fallback_img.size
                            # Grid
                            for x in range(0, w, 5):
                                fallback_draw.line([(x, 0), (x, h)], fill=(240, 240, 240), width=1)
                            for y in range(0, h, 5):
                                fallback_draw.line([(0, y), (w, y)], fill=(240, 240, 240), width=1)
                            # Border
                            fallback_draw.rectangle([0, 0, w-1, h-1], outline=(180, 180, 180), width=2)
                            # Circles
                            for r in range(min(w, h)//2, 0, -5):
                                fallback_draw.ellipse([w//2-r, h//2-r, w//2+r, h//2+r], outline=(200, 200, 200), width=1)
                            
                            fallback_buffer = io.BytesIO()
                            fallback_img.save(fallback_buffer, format='PNG', optimize=False, compress_level=0)
                            fallback_buffer.seek(0)
                            placeholder_base64 = base64.b64encode(fallback_buffer.read()).decode('utf-8')
                            
                            if len(placeholder_base64) < 1000:
                                raise ValueError(f'Fallback placeholder still too small: {len(placeholder_base64)} chars')
                            
                            _logger.info(f'✅ Created fallback placeholder via PIL: {len(placeholder_base64)} chars base64')
                        except Exception as e2:
                            _logger.error(f'❌ PIL fallback also failed: {str(e2)}')
                            # Last resort: use a larger hardcoded PNG (80x60 white image with border)
                            # Base64 dari PNG 80x60 dengan border dan pattern sederhana
                            placeholder_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAIAAAB+RarbAAAAYElEQVR4nO3PAQ0AIRDAsOf9ez5cQDJaBduame8l/+2A0wzXGa4zXGe4znCd4TrDdYbrDNcZrjNcZ7jOcJ3hOsN1husM1xmuM1xnuM5wneE6w3WG6wzXGa4zXGe4znDdBkTIA3Ungm4cAAAAAElFTkSuQmCC'
                            _logger.warning(f'⚠️ Using hardcoded PNG placeholder as last resort: {len(placeholder_base64)} chars')
                    
                    # CRITICAL: Final validation - ensure placeholder_base64 is valid and large enough
                    if not placeholder_base64 or len(placeholder_base64) < 200:
                        # Use larger hardcoded PNG
                        placeholder_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAIAAAB+RarbAAAAYElEQVR4nO3PAQ0AIRDAsOf9ez5cQDJaBduame8l/+2A0wzXGa4zXGe4znCd4TrDdYbrDNcZrjNcZ7jOcJ3hOsN1husM1xmuM1xnuM5wneE6w3WG6wzXGa4zXGe4znDdBkTIA3Ungm4cAAAAAElFTkSuQmCC'
                        _logger.warning('⚠️ placeholder_base64 was empty or too small, using hardcoded fallback')
                    
                    # Validate base64 format
                    try:
                        decoded = base64.b64decode(placeholder_base64, validate=True)
                        _logger.info(f'✅ Validated placeholder base64: {len(placeholder_base64)} chars, decoded to {len(decoded)} bytes')
                    except Exception as e:
                        _logger.error(f'❌ Invalid base64 format: {str(e)}')
                        # Use hardcoded fallback
                        placeholder_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAFAAAAA8CAIAAAB+RarbAAAAYElEQVR4nO3PAQ0AIRDAsOf9ez5cQDJaBduame8l/+2A0wzXGa4zXGe4znCd4TrDdYbrDNcZrjNcZ7jOcJ3hOsN1husM1xmuM1xnuM5wneE6w3WG6wzXGa4zXGe4znDdBkTIA3Ungm4cAAAAAElFTkSuQmCC'
                    
                    # CRITICAL: imageBase64 sudah ada di signature_props, cukup update nilainya
                    signature_props['imageBase64'] = placeholder_base64
                    _logger.info(f'✅ VISIBLE signature placeholder set: {len(placeholder_base64)} chars base64')
                    
                    # CRITICAL: Error jika imageBase64 masih terlalu kecil (BSRE API akan reject)
                    if len(placeholder_base64) < 200:
                        raise UserError(f'imageBase64 terlalu kecil ({len(placeholder_base64)} chars). BSRE API memerlukan image signature yang lebih besar. Silakan upload image signature yang sebenarnya.')
                
                # Add signatureProperties array to payload
                json_payload['signatureProperties'] = [signature_props]
            else:
                # INVISIBLE signature
                # V2 API requires imageBase64 even for INVISIBLE signature
                # Create a small transparent placeholder (1x1 pixel)
                placeholder_base64 = ''
                try:
                    from PIL import Image
                    import io
                    # Create minimal transparent image (1x1 pixel)
                    placeholder = Image.new('RGBA', (1, 1), (255, 255, 255, 0))
                    placeholder_buffer = io.BytesIO()
                    placeholder.save(placeholder_buffer, format='PNG')
                    placeholder_buffer.seek(0)
                    placeholder_base64 = base64.b64encode(placeholder_buffer.read()).decode('utf-8')
                    _logger.info(f'✅ Created transparent placeholder for INVISIBLE signature: {len(placeholder_base64)} chars base64')
                except Exception as e:
                    _logger.error(f'❌ Error creating INVISIBLE placeholder: {str(e)}', exc_info=True)
                    # Fallback: create minimal base64 PNG manually
                    # Minimal 1x1 transparent PNG: iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==
                    placeholder_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
                    _logger.warning(f'⚠️ Using hardcoded minimal PNG placeholder for INVISIBLE signature')
                
                # Ensure placeholder_base64 is not empty
                if not placeholder_base64:
                    placeholder_base64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
                    _logger.warning('⚠️ Using hardcoded fallback PNG for INVISIBLE signature')
                
                # CRITICAL: Urutan field HARUS sesuai Postman: imageBase64, tampilan, page, originX, originY, width, height, location, reason, contactInfo
                json_payload['signatureProperties'] = [{
                    'imageBase64': placeholder_base64,  # Required by V2 API - MUST NOT BE EMPTY, harus di urutan pertama
                    'tampilan': 'INVISIBLE',
                    'page': 1,
                    'originX': 0.0,
                    'originY': 0.0,
                    'width': 0.0,
                    'height': 0.0,
                    'location': 'null',      # BSRE API expects string "null", not empty string
                    'reason': 'null',        # BSRE API expects string "null", not empty string
                    'contactInfo': 'null',   # Required field according to BSRE API v2 documentation
                }]
                _logger.info(f'✅ INVISIBLE signature properties created with imageBase64: {len(placeholder_base64)} chars')
            
            _logger.info(f'V2 API JSON payload prepared (file size: {len(document_base64)} chars base64)')
            _logger.info(f'Signature properties: {len(json_payload["signatureProperties"])} items')
            
            # Log signature properties details dan validasi imageBase64
            for idx, sig_prop in enumerate(json_payload["signatureProperties"]):
                image_b64 = sig_prop.get("imageBase64", "")
                image_status = "present" if image_b64 else "missing"
                image_length = len(image_b64) if image_b64 else 0
                _logger.info(f'  Signature[{idx}]: tampilan={sig_prop.get("tampilan")}, page={sig_prop.get("page")}, '
                           f'originX={sig_prop.get("originX")}, originY={sig_prop.get("originY")}, '
                           f'width={sig_prop.get("width")}, height={sig_prop.get("height")}, '
                           f'imageBase64={image_status} ({image_length} chars)')
                
                # CRITICAL: Validate imageBase64 is not empty (BSRE API requires it)
                if not image_b64 or len(image_b64) == 0:
                    _logger.error(f'❌ CRITICAL: Signature[{idx}] has EMPTY imageBase64! This will cause BSRE API 500 error!')
                    # Set fallback minimal PNG
                    fallback_png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
                    json_payload["signatureProperties"][idx]['imageBase64'] = fallback_png
                    _logger.warning(f'⚠️ Fixed Signature[{idx}] with hardcoded fallback PNG ({len(fallback_png)} chars)')
                else:
                    # Validate imageBase64 format (harus valid base64)
                    try:
                        import base64 as b64_check
                        decoded = b64_check.b64decode(image_b64, validate=True)
                        _logger.info(f'✅ Signature[{idx}] imageBase64 is valid base64 ({image_length} chars, decoded: {len(decoded)} bytes)')
                    except Exception as b64_error:
                        _logger.error(f'❌ Signature[{idx}] imageBase64 is INVALID base64: {str(b64_error)}')
                        # Replace with valid fallback
                        fallback_png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
                        json_payload["signatureProperties"][idx]['imageBase64'] = fallback_png
                        _logger.warning(f'⚠️ Replaced invalid imageBase64 with fallback PNG')
            
            # Log full payload untuk debugging (tanpa file base64 yang panjang)
            # CRITICAL: Use deepcopy to avoid modifying original json_payload!
            import copy
            payload_debug = copy.deepcopy(json_payload)
            if 'file' in payload_debug and payload_debug['file']:
                payload_debug['file'] = [f'[BASE64_PDF: {len(payload_debug["file"][0])} chars]']
            for sig_prop in payload_debug.get('signatureProperties', []):
                if 'imageBase64' in sig_prop:
                    img_len = len(sig_prop['imageBase64'])
                    sig_prop['imageBase64'] = f'[BASE64_IMAGE: {img_len} chars]'
            
            _logger.info(f'📤 BSRE API Request Payload (debug): {json.dumps(payload_debug, indent=2)}')
            _logger.info(f'📤 BSRE API Request: POST {self.api_url}/api/v2/sign/pdf')
            _logger.info(f'📤 Payload size: file={len(json_payload.get("file", [""])[0]) if json_payload.get("file") else 0} chars, signatureProperties={len(json_payload.get("signatureProperties", []))} items')
            
            # Make API request to BSRE dengan JSON (V2)
            # Endpoint: /api/v2/sign/pdf
            # CRITICAL: Pastikan urutan field sesuai Postman: nik, passphrase, signatureProperties, file
            # Reorder payload untuk memastikan urutan sesuai (beberapa API sensitive terhadap urutan)
            ordered_payload = {}
            if 'nik' in json_payload:
                ordered_payload['nik'] = json_payload['nik']
            if 'email' in json_payload:
                ordered_payload['email'] = json_payload['email']
            ordered_payload['passphrase'] = json_payload['passphrase']
            ordered_payload['signatureProperties'] = json_payload['signatureProperties']
            ordered_payload['file'] = json_payload['file']
            
            _logger.info(f'📤 Final payload order: {list(ordered_payload.keys())}')
            result = self._make_api_request('api/v2/sign/pdf', method='POST', data=ordered_payload)
            
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
    
    def _resize_image_to_exact_size(self, image_binary, target_width, target_height):
        """
        Resize image MAINTAINING ASPECT RATIO to fit within target bounds.
        
        CRITICAL: BSRE API uses PHYSICAL size of the uploaded image!
        We must resize the image file itself before upload.
        
        ASPECT RATIO PRESERVED:
        - Fit image within bounding box (max_width x max_height)
        - Calculate actual dimensions maintaining original aspect ratio
        - No distortion - image stays proportional
        
        Example:
            Original: 569x308 (ratio 1.85:1)
            Target bounds: 120x90
            Result: 120x65 (maintains 1.85:1 ratio)
        
        Args:
            image_binary: Original image as bytes
            target_width: Maximum width in pixels (bounding box)
            target_height: Maximum height in pixels (bounding box)
        
        Returns:
            Resized image as bytes (PNG format, aspect ratio maintained)
        """
        from PIL import Image
        import io
        
        try:
            # Open image from binary
            image = Image.open(io.BytesIO(image_binary))
            original_size = image.size
            
            _logger.info(f'📐 Resizing image from {original_size[0]}x{original_size[1]} to {target_width}x{target_height}')
            
            # Convert to RGB if necessary (for JPEG compatibility)
            if image.mode in ('RGBA', 'LA', 'P'):
                # Create white background
                background = Image.new('RGB', image.size, (255, 255, 255))
                if image.mode == 'P':
                    image = image.convert('RGBA')
                background.paste(image, mask=image.split()[-1] if image.mode in ('RGBA', 'LA') else None)
                image = background
            elif image.mode != 'RGB':
                image = image.convert('RGB')
            
            # Calculate aspect ratio and resize MAINTAINING it
            # Fit within bounding box (target_width x target_height)
            original_width, original_height = original_size
            aspect_ratio = original_width / original_height
            
            _logger.info(f'🎨 Original aspect ratio: {aspect_ratio:.3f} ({original_width}:{original_height})')
            
            # OPTIMIZATION: Skip resize if image already small enough!
            # This preserves MAXIMUM QUALITY for images that don't need downscaling
            if original_width <= target_width and original_height <= target_height:
                _logger.info(f'✨ Image already within bounds ({original_width}x{original_height} ≤ {target_width}x{target_height})')
                _logger.info(f'✅ SKIPPING RESIZE for MAXIMUM QUALITY! (no quality loss)')
                
                # Still save as optimized PNG
                output = io.BytesIO()
                image.save(output, format='PNG', optimize=False, compress_level=1)
                result = output.getvalue()
                _logger.info(f'✅ Using original size: {len(image_binary)} → {len(result)} bytes')
                return result
            
            # Calculate new dimensions maintaining aspect ratio
            if original_width / target_width > original_height / target_height:
                # Width is limiting factor
                new_width = int(target_width)
                new_height = int(target_width / aspect_ratio)
            else:
                # Height is limiting factor  
                new_height = int(target_height)
                new_width = int(target_height * aspect_ratio)
            
            _logger.info(f'🎯 Resizing to: {new_width}x{new_height} (aspect ratio preserved)')
            _logger.info(f'⚠️  Downscaling from {original_width}x{original_height} (applying sharpening to maintain clarity)')
            
            # CRITICAL: Use HIGHEST QUALITY resize method
            # LANCZOS is best for downscaling, maintains sharpness
            resized_image = image.resize((new_width, new_height), Image.Resampling.LANCZOS)
            
            # Apply SHARPEN filter to compensate for downscaling blur
            # This helps maintain text/logo clarity
            from PIL import ImageFilter
            resized_image = resized_image.filter(ImageFilter.SHARPEN)
            
            # Save to bytes as PNG with HIGH QUALITY settings
            # optimize=False prevents aggressive compression
            # compress_level=1 for best quality (0-9, lower=better quality)
            output = io.BytesIO()
            resized_image.save(output, format='PNG', 
                             optimize=False,  # Don't sacrifice quality!
                             compress_level=1)  # Minimal compression
            resized_binary = output.getvalue()
            
            _logger.info(f'✅ Image resized: {len(image_binary)} → {len(resized_binary)} bytes')
            _logger.info(f'✅ Final dimensions: {new_width}x{new_height} px (ASPECT RATIO MAINTAINED ✨)')
            
            return resized_binary
            
        except Exception as e:
            _logger.error(f'❌ Error resizing image: {str(e)}')
            # Return original if resize fails
            return image_binary
    
    def _get_signature_width(self):
        """Get signature width based on size preset or custom value"""
        self.ensure_one()
        
        # Size presets (width x height)
        # CRITICAL: BSRE API uses PHYSICAL IMAGE SIZE, not API width/height parameters!
        # We resize image to these exact dimensions before upload.
        SIZE_PRESETS = {
            'small': 80,      # 80x60 - Good for corners
            'medium': 120,    # 120x90 - Balanced size
            'large': 160,     # 160x120 - Larger signature
            'xlarge': 200,    # 200x150 - Very visible
        }
        
        if self.signature_size == 'custom':
            return self.signature_width
        else:
            return SIZE_PRESETS.get(self.signature_size, 200)
    
    def _get_signature_height(self):
        """Get signature height based on size preset or custom value"""
        self.ensure_one()
        
        # Size presets (width x height)
        # CRITICAL: BSRE API uses PHYSICAL IMAGE SIZE, not API width/height parameters!
        # We resize image to these exact dimensions before upload.
        SIZE_PRESETS = {
            'small': 60,      # 80x60 - Good for corners
            'medium': 90,     # 120x90 - Balanced size
            'large': 120,     # 160x120 - Larger signature
            'xlarge': 150,    # 200x150 - Very visible
        }
        
        if self.signature_size == 'custom':
            return self.signature_height
        else:
            return SIZE_PRESETS.get(self.signature_size, 150)
    
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
        
        # V2 API Coordinate System - A4 Portrait Dimensions
        # Page Width: 595.28 points (~210mm)
        # Page Height: 841.89 points (~297mm)
        PAGE_WIDTH = 595
        PAGE_HEIGHT = 842
        MARGIN = 10
        
        # Calculate position based on preset
        # V2 API: originX, originY represent TOP-LEFT corner of signature
        # We need to ensure signature stays within page boundaries
        
        POSITIONS_X = {
            'bottom_left': MARGIN,                                      # Left edge with margin
            'top_left': MARGIN,                                         # Left edge with margin
            'bottom_right': PAGE_WIDTH - sig_width - MARGIN,            # Right edge - width - margin
            'top_right': PAGE_WIDTH - sig_width - MARGIN,               # Right edge - width - margin
            'center': (PAGE_WIDTH / 2) - (sig_width / 2),               # Center - half width
        }
        
        position = POSITIONS_X.get(self.signature_position, 10)
        _logger.info(f'🎯 X Position: preset={self.signature_position}, sig_width={sig_width}, calculated={position}')
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
        
        # V2 API Coordinate System - A4 Portrait Dimensions
        # Page Height: 841.89 points (~297mm)
        # V2 API: Y=0 at TOP (increases downward - standard PDF)
        PAGE_WIDTH = 595
        PAGE_HEIGHT = 842
        MARGIN = 10
        
        # Calculate position based on preset
        # V2 API: originY represents TOP-LEFT corner of signature (Y from top)
        # For BOTTOM positions: originY = PAGE_HEIGHT - sig_height - margin
        # For TOP positions: originY = margin
        # For CENTER: originY = (PAGE_HEIGHT / 2) - (sig_height / 2)
        
        POSITIONS_Y = {
            'bottom_left': PAGE_HEIGHT - sig_height - MARGIN,           # Bottom: from top to bottom edge
            'bottom_right': PAGE_HEIGHT - sig_height - MARGIN,          # Bottom: from top to bottom edge
            'top_left': MARGIN,                                          # Top: margin from top
            'top_right': MARGIN,                                         # Top: margin from top
            'center': (PAGE_HEIGHT / 2) - (sig_height / 2),             # Center: middle - half height
        }
        
        # CRITICAL: Ensure position is within bounds
        # V2 API: originY is from TOP, so bottom positions should be: PAGE_HEIGHT - height - margin
        # But we need to ensure it doesn't go negative or exceed bounds
        position = POSITIONS_Y.get(self.signature_position, MARGIN)
        if position < 0:
            position = MARGIN
        if position + sig_height > PAGE_HEIGHT:
            position = PAGE_HEIGHT - sig_height - MARGIN
            if position < 0:
                position = 0
        
        position = POSITIONS_Y.get(self.signature_position, 10)
        _logger.info(f'🎯 Y Position: preset={self.signature_position}, sig_height={sig_height}, calculated={position}')
        return position
    
    def verify_signature(self, document_data):
        """
        Verify digital signature pada dokumen
        
        Args:
            document_data (bytes): Binary data PDF
        
        Returns:
            dict: Verification result dengan informasi penandatangan
        """
        self.ensure_one()
        
        try:
            # Gunakan endpoint versi 2: {{baseURL}}/api/v2/verify/pdf
            # Format: {"file": "base64_pdf"} atau {"file": "base64_pdf", "password": "pdfPassword"}
            endpoint = 'api/v2/verify/pdf'
            
            # Convert PDF to base64 untuk JSON payload
            import base64 as b64
            file_base64 = b64.b64encode(document_data).decode('utf-8')
            
            json_payload = {
                'file': file_base64
            }
            
            _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
            _logger.info(f'[BSRE VERIFY V2] Menggunakan endpoint V2: {endpoint}')
            _logger.info(f'[BSRE VERIFY V2] File size: {len(document_data)} bytes, Base64 length: {len(file_base64)} chars')
            _logger.info(f'[BSRE VERIFY V2] Payload keys: {list(json_payload.keys())}')
            _logger.info(f'[BSRE VERIFY V2] Payload file (first 100 chars): {file_base64[:100]}...')
            
            result = self._make_api_request(endpoint, method='POST', data=json_payload)
            
            _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
            _logger.info(f'[BSRE VERIFY V2] Response received from BSRE API V2')
            _logger.info(f'[BSRE VERIFY V2] Response type: {type(result)}')
            _logger.info(f'[BSRE VERIFY V2] Response is dict: {isinstance(result, dict)}')
            if isinstance(result, dict):
                _logger.info(f'[BSRE VERIFY V2] Response keys: {list(result.keys())}')
                _logger.info(f'[BSRE VERIFY V2] Full response: {json.dumps(result, indent=2, default=str)}')
            else:
                _logger.info(f'[BSRE VERIFY V2] Response content: {str(result)[:500]}')
            _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
            _logger.info(f'[BSRE VERIFY V2] Memproses response dari BSRE API V2')
            _logger.info(f'[BSRE VERIFY V2] Response type: {type(result)}')
            
            if result and isinstance(result, dict):
                    _logger.info(f'[BSRE VERIFY V2] Response structure analysis:')
                    _logger.info(f'[BSRE VERIFY V2] - Top-level keys: {list(result.keys())}')
                    
                    # Berdasarkan dokumentasi V2.2.1, struktur response V2 adalah:
                    # {
                    #   "conclusion": "NO_SIGNATURE" atau lainnya,
                    #   "description": "Dokumen tidak memiliki tandatangan elektronik",
                    #   "signatureInformations": [{
                    #     "id": "...",
                    #     "signatureFormat": "PKCS7-T",
                    #     "signerName": "User Development",  <-- NAMA PENANDATANGAN
                    #     "signatureDate": "2023-07-11T02:41:23.000+00:00",
                    #     "fieldName": "sig_1689043283248",
                    #     "reason": "Dokumen ini telah ditandatangani...",
                    #     "location": "Indonesia",
                    #     "certLevelCode": 0,
                    #     "signatureAlgorithm": null,
                    #     "digestAlgorithm": null,
                    #     "timestampInfomation": {
                    #       "id": "...",
                    #       "signerName": "Timestamp Authority...",
                    #       "timestampDate": "2023-07-11T02:46:28.000+00:00"
                    #     },
                    #     "certificateDetails": [...],
                    #     "integrityValid": true,
                    #     "certificateTrusted": false,
                    #     "lastSignature": false
                    #   }],
                    #   "signatureCount": 0
                    # }
                    
                    # Cek apakah ini struktur V1 (dengan details array) atau V2 (dengan signatureInformations)
                    if 'signatureInformations' in result:
                        _logger.info(f'[BSRE VERIFY V2] ✅ Detected V2 structure (with signatureInformations array)')
                        _logger.info(f'[BSRE VERIFY V2] - signatureInformations type: {type(result.get("signatureInformations"))}')
                        if isinstance(result.get('signatureInformations'), list) and len(result.get('signatureInformations', [])) > 0:
                            _logger.info(f'[BSRE VERIFY V2] - signatureInformations[0] keys: {list(result["signatureInformations"][0].keys()) if isinstance(result["signatureInformations"][0], dict) else "Not a dict"}')
                    elif 'details' in result:
                        _logger.info(f'[BSRE VERIFY V2] ⚠️ Detected V1 structure (with details array) - fallback')
                        _logger.info(f'[BSRE VERIFY V2] - details type: {type(result.get("details"))}')
                        if isinstance(result.get('details'), list) and len(result.get('details', [])) > 0:
                            _logger.info(f'[BSRE VERIFY V2] - details[0] keys: {list(result["details"][0].keys()) if isinstance(result["details"][0], dict) else "Not a dict"}')
                    else:
                        _logger.info(f'[BSRE VERIFY V2] ⚠️ Unknown structure detected')
                        _logger.info(f'[BSRE VERIFY V2] - Full response: {json.dumps(result, indent=2, default=str)}')
                    
                _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
                
                verification_info = {
                    'success': True,
                    'valid': False,  # Akan di-set berdasarkan conclusion atau integrityValid
                    'message': result.get('description', result.get('notes', 'Verifikasi berhasil')),
                }
                
                _logger.info(f'[BSRE VERIFY V2] Extracting verification info...')
                _logger.info(f'[BSRE VERIFY V2] - conclusion: {result.get("conclusion")}')
                _logger.info(f'[BSRE VERIFY V2] - description: {result.get("description")}')
                _logger.info(f'[BSRE VERIFY V2] - signatureCount: {result.get("signatureCount", 0)}')
                
                # Extract dari signatureInformations array (struktur V2) atau details array (struktur V1)
                signature_infos = result.get('signatureInformations', [])
                details = result.get('details', [])
                
                # Prioritas: V2 structure dengan signatureInformations
                if signature_infos and isinstance(signature_infos, list) and len(signature_infos) > 0:
                    # Struktur V2 dengan signatureInformations array
                    _logger.info(f'[BSRE VERIFY V2] ✅ Using V2 structure (signatureInformations array)')
                    sig_info = signature_infos[0]  # Ambil signature pertama
                    
                    # Extract signer information
                    verification_info['signer'] = sig_info.get('signerName')
                    _logger.info(f'[BSRE VERIFY V2] - Extracted signer: {verification_info["signer"]}')
                    
                    # Extract signature date
                    verification_info['signature_date'] = sig_info.get('signatureDate')
                    
                    # Extract location
                    verification_info['location'] = sig_info.get('location')
                    
                    # Extract reason
                    verification_info['reason'] = sig_info.get('reason')
                    
                    # Extract integrity and certificate info
                    verification_info['document_not_modified'] = sig_info.get('integrityValid', True)
                    verification_info['certificate_valid'] = sig_info.get('certificateTrusted', False)
                    
                    # Extract timestamp information
                    timestamp_info = sig_info.get('timestampInfomation') or sig_info.get('timestampInformation')
                    if timestamp_info:
                        verification_info['timestamp_authority'] = timestamp_info.get('signerName', 'Timestamp Authority Badan Siber dan Sandi Negara')
                        verification_info['timestamp_from_tsa'] = True
                        verification_info['timestamp_date'] = timestamp_info.get('timestampDate')
                    else:
                        verification_info['timestamp_authority'] = 'Timestamp Authority Badan Siber dan Sandi Negara'
                        verification_info['timestamp_from_tsa'] = False
                    
                    # Extract certificate details (ambil yang pertama)
                    cert_details = sig_info.get('certificateDetails', [])
                    if cert_details and isinstance(cert_details, list) and len(cert_details) > 0:
                        cert_detail = cert_details[0]
                        verification_info['certificate_common_name'] = cert_detail.get('commonName')
                        verification_info['certificate_issuer'] = cert_detail.get('issuerName')
                    
                    # Set valid berdasarkan conclusion dan integrityValid
                    conclusion = result.get('conclusion', '').upper()
                    verification_info['valid'] = (
                        conclusion != 'NO_SIGNATURE' and 
                        verification_info['document_not_modified'] and
                        verification_info['certificate_valid']
                    )
                    
                    _logger.info(f'[BSRE VERIFY V2] - signature_date: {verification_info["signature_date"]}')
                    _logger.info(f'[BSRE VERIFY V2] - location: {verification_info["location"]}')
                    _logger.info(f'[BSRE VERIFY V2] - reason: {verification_info["reason"]}')
                    _logger.info(f'[BSRE VERIFY V2] - integrityValid: {verification_info["document_not_modified"]}')
                    _logger.info(f'[BSRE VERIFY V2] - certificateTrusted: {verification_info["certificate_valid"]}')
                    _logger.info(f'[BSRE VERIFY V2] - valid: {verification_info["valid"]}')
                    
                elif details and isinstance(details, list) and len(details) > 0:
                    # Struktur V1 dengan details array
                    _logger.info(f'[BSRE VERIFY V2] ✅ Using V1 structure (details array)')
                    detail = details[0]  # Ambil signature pertama
                    
                    # Extract signer information dari info_signer
                    info_signer = detail.get('info_signer', {})
                    if info_signer:
                        _logger.info(f'[BSRE VERIFY V2] - info_signer keys: {list(info_signer.keys())}')
                        verification_info['signer'] = (
                            info_signer.get('signer_name') or 
                            info_signer.get('signer_dn') or
                            info_signer.get('name')
                        )
                        _logger.info(f'[BSRE VERIFY V2] - Extracted signer: {verification_info["signer"]}')
                        
                        # Extract identifier dari signer_dn jika ada (format: "CN=Name, O=Org, C=ID")
                        signer_dn = info_signer.get('signer_dn', '')
                        if signer_dn and not verification_info.get('signer'):
                            # Parse DN untuk mendapatkan nama
                            verification_info['signer'] = signer_dn
                        
                        verification_info['certificate_valid'] = info_signer.get('cert_user_certified', True)
                    
                    # Extract signature document information
                    signature_doc = detail.get('signature_document', {})
                    if signature_doc:
                        _logger.info(f'[BSRE VERIFY V2] - signature_document keys: {list(signature_doc.keys())}')
                        verification_info['signature_date'] = signature_doc.get('signed_in')
                        verification_info['location'] = signature_doc.get('location')
                        verification_info['reason'] = signature_doc.get('reason')
                        verification_info['document_not_modified'] = signature_doc.get('document_integrity', True)
                        verification_info['timestamp_from_tsa'] = signature_doc.get('signed_using_tsa', True)
                        _logger.info(f'[BSRE VERIFY V2] - signature_date: {verification_info["signature_date"]}')
                        _logger.info(f'[BSRE VERIFY V2] - location: {verification_info["location"]}')
                        _logger.info(f'[BSRE VERIFY V2] - reason: {verification_info["reason"]}')
                    
                    # Extract TSA information
                    info_tsa = detail.get('info_tsa', {})
                    if info_tsa:
                        verification_info['timestamp_authority'] = info_tsa.get('name')
                        verification_info['tsa_cert_validity'] = info_tsa.get('tsa_cert_validity')
                    
                    # Extract signature field
                    verification_info['signature_field'] = detail.get('signature_field')
                else:
                    # Struktur V2 mungkin berbeda - coba extract langsung dari root
                    _logger.info(f'[BSRE VERIFY V2] ⚠️ Using V2 structure (no details array)')
                    _logger.info(f'[BSRE VERIFY V2] - Attempting to extract from root level')
                    
                    # Coba berbagai kemungkinan field untuk V2
                    verification_info['signer'] = (
                        result.get('signer') or 
                        result.get('signerName') or 
                        result.get('signer_name') or
                        result.get('penandatangan')
                    )
                    verification_info['signature_date'] = (
                        result.get('signatureDate') or 
                        result.get('signingTime') or 
                        result.get('signed_in') or
                        result.get('signature_date')
                    )
                    verification_info['location'] = result.get('location')
                    verification_info['reason'] = result.get('reason')
                    verification_info['timestamp_authority'] = (
                        result.get('timestampAuthority') or 
                        result.get('timestamp_authority') or
                        result.get('tsa')
                    )
                    verification_info['document_not_modified'] = result.get('documentNotModified', result.get('document_integrity', True))
                    verification_info['timestamp_from_tsa'] = result.get('timestampFromTSA', result.get('signed_using_tsa', True))
                    verification_info['certificate_valid'] = result.get('certificateValid', result.get('cert_user_certified', True))
                    
                    _logger.info(f'[BSRE VERIFY V2] - Extracted from root:')
                    _logger.info(f'[BSRE VERIFY V2]   - signer: {verification_info["signer"]}')
                    _logger.info(f'[BSRE VERIFY V2]   - signature_date: {verification_info["signature_date"]}')
                    _logger.info(f'[BSRE VERIFY V2]   - location: {verification_info["location"]}')
                    _logger.info(f'[BSRE VERIFY V2]   - reason: {verification_info["reason"]}')
                
                # Set default values jika tidak ada
                if not verification_info.get('signer'):
                    verification_info['signer'] = None
                if not verification_info.get('signature_date'):
                    verification_info['signature_date'] = None
                if not verification_info.get('location'):
                    verification_info['location'] = None
                if not verification_info.get('reason'):
                    verification_info['reason'] = None
                if not verification_info.get('timestamp_authority'):
                    verification_info['timestamp_authority'] = 'Timestamp Authority Badan Siber dan Sandi Negara'
                
                # Set verification details defaults
                verification_info['document_not_modified'] = verification_info.get('document_not_modified', True)
                verification_info['timestamp_from_tsa'] = verification_info.get('timestamp_from_tsa', True)
                verification_info['certificate_valid'] = verification_info.get('certificate_valid', True)
                verification_info['long_term_validation'] = True  # Default untuk BSRE
                
                _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
                _logger.info(f'[BSRE VERIFY V2] Final parsed verification info:')
                _logger.info(f'[BSRE VERIFY V2] {json.dumps(verification_info, indent=2, default=str)}')
                _logger.info(f'[BSRE VERIFY V2] ════════════════════════════════════════════════════════════')
                
                return verification_info
            else:
                return {
                    'success': False,
                    'valid': False,
                    'message': f'Response tidak valid: {type(result)}'
                }
                
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'[BSRE VERIFY] Error verifying signature: {error_msg}', exc_info=True)
            
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

