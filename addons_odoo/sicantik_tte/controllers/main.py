# -*- coding: utf-8 -*-

from odoo import http, fields
from odoo.http import request, Response, content_disposition
from odoo.exceptions import UserError, AccessError
import logging
import base64
import json
from datetime import timedelta

_logger = logging.getLogger(__name__)


class SicantikTTEController(http.Controller):
    """
    Controller untuk handle download dokumen dari MinIO dan verifikasi publik
    """
    
    @http.route([
        '/sicantik/tte/download/<path:document_number>/<token>',
    ], type='http', auth='public', methods=['GET'], csrf=False)
    def download_document_public(self, document_number, token, filename=None, **kwargs):
        """
        Public download endpoint dengan token verification
        Mencegah DDoS dengan token yang expire setelah 1 jam
        
        Args:
            document_number: Nomor dokumen
            token: Download token yang di-generate saat verifikasi
            filename: Nama file untuk download (optional)
        
        Returns:
            HTTP Response dengan file data atau error
        """
        try:
            _logger.info(f'[DOWNLOAD] Request download dengan token: document={document_number}, token={token[:8]}...')
            
            # Normalize document_number
            document_number = document_number.strip().strip('/')
            
            # Get document dengan sudo untuk bypass access rights
            Document = request.env['sicantik.document'].sudo()
            document = Document.search([
                ('document_number', '=', document_number),
                ('download_token', '=', token)
            ], limit=1)
            
            if not document.exists():
                _logger.warning(f'[DOWNLOAD] Dokumen tidak ditemukan atau token tidak valid: {document_number}')
                return Response(
                    'Dokumen tidak ditemukan atau token tidak valid',
                    status=404
                )
            
            # Check token expiration
            if document.download_token_expires and fields.Datetime.now() > document.download_token_expires:
                _logger.warning(f'[DOWNLOAD] Token sudah expired untuk dokumen {document_number}')
                return Response(
                    'Token download sudah kadaluarsa. Silakan scan QR code lagi untuk mendapatkan token baru.',
                    status=403
                )
            
            # Check state - hanya allow signed atau verified
            if document.state not in ['signed', 'verified']:
                _logger.warning(f'[DOWNLOAD] Dokumen belum ditandatangani: {document.state}')
                return Response(
                    'Dokumen belum ditandatangani',
                    status=403
                )
            
            # Check if document has MinIO object
            if not document.minio_object_name:
                _logger.error(f'[DOWNLOAD] Document {document.id} does not have MinIO object')
                return Response(
                    'Dokumen belum diupload ke storage',
                    status=404
                )
            
            # Get MinIO connector
            MinioConnector = request.env['minio.connector'].sudo()
            minio_connector = MinioConnector.search([], limit=1)
            
            if not minio_connector:
                _logger.error('[DOWNLOAD] MinIO connector not found')
                return Response(
                    'Konfigurasi MinIO tidak ditemukan',
                    status=500
                )
            
            # Download file from MinIO
            _logger.info(f'[DOWNLOAD] Downloading document {document.id} from MinIO: {document.minio_object_name}')
            
            download_result = minio_connector.download_file(
                bucket_name=document.minio_bucket,
                object_name=document.minio_object_name
            )
            
            if not download_result.get('success'):
                error_msg = download_result.get('message', 'Unknown error')
                _logger.error(f'[DOWNLOAD] Failed to download from MinIO: {error_msg}')
                return Response(
                    f'Gagal download dari MinIO: {error_msg}',
                    status=500
                )
            
            # Get file data
            file_data = download_result['data']
            
            # Determine filename
            if not filename:
                filename = document.original_filename or 'document.pdf'
            
            # Ensure filename is safe and has .pdf extension
            if not filename.endswith('.pdf'):
                filename = filename.rsplit('.', 1)[0] + '.pdf'
            
            _logger.info(f'[DOWNLOAD] Document {document.id} downloaded successfully: {filename} ({len(file_data)} bytes)')
            
            # Return file as HTTP response using request.make_response (Odoo standard)
            # This ensures proper binary handling and streaming
            return request.make_response(
                file_data,
                headers=[
                    ('Content-Type', 'application/pdf'),
                    ('Content-Disposition', content_disposition(filename)),
                    ('Content-Length', str(len(file_data))),
                    ('X-Content-Type-Options', 'nosniff'),
                ]
            )
            
        except Exception as e:
            _logger.error(f'[DOWNLOAD] Error downloading document {document_number}: {str(e)}', exc_info=True)
            return Response(
                f'Error download dokumen: {str(e)}',
                status=500
            )
    
    @http.route([
        '/web/content/sicantik.document/<int:document_id>/download',
    ], type='http', auth='user', methods=['GET'])
    def download_document(self, document_id, filename=None, **kwargs):
        """
        Download dokumen dari MinIO storage
        
        Args:
            document_id: ID dokumen
            filename: Nama file untuk download (optional)
        
        Returns:
            HTTP Response dengan file data
        """
        try:
            # Get document record
            Document = request.env['sicantik.document'].sudo()
            document = Document.browse(document_id)
            
            if not document.exists():
                return request.not_found()
            
            # Check access rights
            try:
                document.check_access_rights('read')
                document.check_access_rule('read')
            except AccessError:
                return request.render('http_routing.403')
            
            # Check if document has MinIO object
            if not document.minio_object_name:
                _logger.error(f'Document {document_id} does not have MinIO object')
                return Response(
                    'Dokumen belum diupload ke storage',
                    status=404
                )
            
            # Get MinIO connector
            MinioConnector = request.env['minio.connector'].sudo()
            minio_connector = MinioConnector.search([], limit=1)
            
            if not minio_connector:
                _logger.error('MinIO connector not found')
                return Response(
                    'Konfigurasi MinIO tidak ditemukan',
                    status=500
                )
            
            # Download file from MinIO
            _logger.info(f'Downloading document {document_id} from MinIO: {document.minio_object_name}')
            
            download_result = minio_connector.download_file(
                bucket_name=document.minio_bucket,
                object_name=document.minio_object_name
            )
            
            if not download_result.get('success'):
                error_msg = download_result.get('message', 'Unknown error')
                _logger.error(f'Failed to download from MinIO: {error_msg}')
                return Response(
                    f'Gagal download dari MinIO: {error_msg}',
                    status=500
                )
            
            # Get file data
            file_data = download_result['data']
            
            # Determine filename
            if not filename:
                filename = document.original_filename or 'document.pdf'
            
            # Prepare response headers
            headers = [
                ('Content-Type', 'application/pdf'),
                ('Content-Disposition', f'attachment; filename="{filename}"'),
                ('Content-Length', len(file_data)),
            ]
            
            _logger.info(f'Document {document_id} downloaded successfully: {filename} ({len(file_data)} bytes)')
            
            # Return file as HTTP response
            return Response(
                file_data,
                headers=headers,
                status=200
            )
            
        except Exception as e:
            _logger.error(f'Error downloading document {document_id}: {str(e)}', exc_info=True)
            return Response(
                f'Error download dokumen: {str(e)}',
                status=500
            )
    
    @http.route([
        '/sicantik/tte/verify/<path:document_number>',  # Gunakan <path:> untuk handle slash dalam document_number
        '/sicantik/tte/verify/<path:document_number>/',  # Dengan trailing slash untuk kompatibilitas
    ], type='http', auth='public', website=True, methods=['GET'], csrf=False)
    def verify_document(self, document_number, **kwargs):
        """
        Public endpoint untuk verifikasi dokumen
        
        Args:
            document_number: Nomor dokumen (dapat mengandung slash seperti DOC/2025/00003)
        
        Returns:
            Halaman verifikasi dokumen
        """
        try:
            _logger.info(f'[VERIFY] ==========================================')
            _logger.info(f'[VERIFY] Controller method called!')
            _logger.info(f'[VERIFY] Request untuk verifikasi dokumen: {document_number}')
            _logger.info(f'[VERIFY] URL: {request.httprequest.url}')
            _logger.info(f'[VERIFY] Full path: {request.httprequest.path}')
            _logger.info(f'[VERIFY] Method: {request.httprequest.method}')
            _logger.info(f'[VERIFY] User Agent: {request.httprequest.headers.get("User-Agent", "N/A")}')
            
            # Normalize document_number (remove leading/trailing spaces dan slash)
            document_number = document_number.strip().strip('/')
            
            # Get document dengan sudo untuk bypass access rights
            Document = request.env['sicantik.document'].sudo()
            
            # Cari dokumen dengan document_number (exact match)
            document = Document.search([
                ('document_number', '=', document_number)
            ], limit=1)
            
            _logger.info(f'[VERIFY] Dokumen ditemukan: {bool(document)}')
            if document:
                _logger.info(f'[VERIFY] - ID: {document.id}')
                _logger.info(f'[VERIFY] - State: {document.state}')
                _logger.info(f'[VERIFY] - Name: {document.name}')
                _logger.info(f'[VERIFY] - Signature Date: {document.signature_date}')
            else:
                # Coba search tanpa filter state untuk debugging
                all_docs = Document.search([
                    ('document_number', '=', document_number)
                ], limit=5)
                _logger.warning(f'[VERIFY] Dokumen tidak ditemukan dengan document_number={document_number}')
                _logger.warning(f'[VERIFY] Total dokumen dengan nomor serupa: {len(all_docs)}')
                if all_docs:
                    for doc in all_docs:
                        _logger.warning(f'[VERIFY] - Found: {doc.document_number}, State: {doc.state}, ID: {doc.id}')
                else:
                    # Coba search dengan ilike untuk debugging
                    similar_docs = Document.search([
                        ('document_number', 'ilike', f'%{document_number}%')
                    ], limit=10)
                    _logger.warning(f'[VERIFY] Total dokumen dengan nomor mirip: {len(similar_docs)}')
                    for doc in similar_docs[:5]:
                        _logger.warning(f'[VERIFY] - Similar: {doc.document_number}, State: {doc.state}')
                
                return request.render('sicantik_tte.verification_not_found', {
                    'document_number': document_number
                })
            
            # Check state - hanya allow signed atau verified
            if document.state not in ['signed', 'verified']:
                _logger.warning(f'[VERIFY] Dokumen ditemukan tapi state tidak valid: {document.state}')
                return request.render('sicantik_tte.verification_not_found', {
                    'document_number': document_number,
                    'message': f'Dokumen belum ditandatangani. Status: {document.state}'
                })
            
            _logger.info(f'[VERIFY] Dokumen valid, state: {document.state}, ID: {document.id}')
            
            # Verifikasi ke BSRE API
            verify_result = None
            bsre_verification_success = False
            
            try:
                # Get BSRE config
                BsreConfig = request.env['bsre.config'].sudo()
                bsre_config = BsreConfig.search([('active', '=', True)], limit=1)
                
                if bsre_config and document.minio_object_name:
                    _logger.info(f'[VERIFY] Memulai verifikasi ke BSRE API...')
                    
                    # Download dokumen dari MinIO untuk verifikasi
                    MinioConnector = request.env['minio.connector'].sudo()
                    minio_connector = MinioConnector.search([], limit=1)
                    
                    if minio_connector:
                        download_result = minio_connector.download_file(
                            bucket_name=document.minio_bucket,
                            object_name=document.minio_object_name
                        )
                        
                        if download_result.get('success'):
                            file_data = download_result['data']
                            _logger.info(f'[VERIFY] Dokumen berhasil di-download dari MinIO untuk verifikasi: {len(file_data)} bytes')
                            
                            # Verifikasi ke BSRE API
                            verify_result = bsre_config.verify_signature(file_data)
                            
                            if verify_result.get('success'):
                                bsre_verification_success = True
                                _logger.info(f'[VERIFY] ✅ BSRE API Verification berhasil')
                                _logger.info(f'[VERIFY] - Valid: {verify_result.get("valid")}')
                                _logger.info(f'[VERIFY] - Signer: {verify_result.get("signer")}')
                                _logger.info(f'[VERIFY] - Signer Identifier: {verify_result.get("signer_identifier")}')
                                _logger.info(f'[VERIFY] - Signature Date: {verify_result.get("signature_date")}')
                                _logger.info(f'[VERIFY] - Location: {verify_result.get("location")}')
                                _logger.info(f'[VERIFY] - Reason: {verify_result.get("reason")}')
                                _logger.info(f'[VERIFY] - Timestamp Authority: {verify_result.get("timestamp_authority")}')
                                _logger.info(f'[VERIFY] - Full verify_result keys: {list(verify_result.keys()) if isinstance(verify_result, dict) else "Not a dict"}')
                                
                                # Update dokumen dengan informasi dari BSRE verify response
                                update_vals = {}
                                
                                # Ambil informasi penandatangan dari response verify
                                signer_info = verify_result.get('signer')
                                if signer_info:
                                    if isinstance(signer_info, dict):
                                        # Jika signer adalah dict, ambil nama dan identifier
                                        signer_name = signer_info.get('name') or signer_info.get('nama') or ''
                                        signer_identifier = signer_info.get('nik') or signer_info.get('email') or signer_info.get('identifier') or ''
                                    elif isinstance(signer_info, str):
                                        # Jika signer adalah string, gunakan sebagai nama
                                        signer_name = signer_info
                                        signer_identifier = document.bsre_signer_identifier or ''
                                    else:
                                        signer_name = str(signer_info)
                                        signer_identifier = document.bsre_signer_identifier or ''
                                    
                                    if signer_name:
                                        update_vals['bsre_signer_name'] = signer_name
                                    if signer_identifier:
                                        update_vals['bsre_signer_identifier'] = signer_identifier
                                
                                # Update certificate jika ada di response
                                certificate_info = verify_result.get('certificate')
                                if certificate_info:
                                    if isinstance(certificate_info, dict):
                                        cert_owner = certificate_info.get('owner') or certificate_info.get('name') or certificate_info.get('pemilik') or ''
                                        if cert_owner and not update_vals.get('bsre_signer_name'):
                                            update_vals['bsre_signer_name'] = cert_owner
                                    
                                    # Update certificate jika belum ada
                                    if not document.bsre_certificate:
                                        update_vals['bsre_certificate'] = str(certificate_info) if not isinstance(certificate_info, dict) else json.dumps(certificate_info)
                                
                                # Update dokumen jika ada perubahan
                                if update_vals:
                                    document.write(update_vals)
                                    _logger.info(f'[VERIFY] Dokumen di-update dengan informasi dari BSRE verify: {update_vals}')
                            else:
                                _logger.warning(f'[VERIFY] ⚠️ BSRE API Verification gagal: {verify_result.get("message")}')
                        else:
                            _logger.warning(f'[VERIFY] ⚠️ Gagal download dokumen dari MinIO untuk verifikasi')
                    else:
                        _logger.warning(f'[VERIFY] ⚠️ MinIO connector tidak ditemukan')
                else:
                    if not bsre_config:
                        _logger.warning(f'[VERIFY] ⚠️ BSRE config tidak ditemukan atau tidak aktif')
                    if not document.minio_object_name:
                        _logger.warning(f'[VERIFY] ⚠️ Dokumen tidak memiliki minio_object_name')
            except Exception as verify_error:
                _logger.error(f'[VERIFY] ❌ Error saat verifikasi ke BSRE API: {str(verify_error)}', exc_info=True)
                # Continue dengan verifikasi lokal meskipun BSRE verify gagal
            
            # Generate download token jika belum ada atau sudah expired
            if not document.download_token or (document.download_token_expires and fields.Datetime.now() > document.download_token_expires):
                document._generate_download_token()
            
            # Update verification counter
            document.write({
                'verification_count': document.verification_count + 1,
                'last_verified_date': fields.Datetime.now()
            })
            
            _logger.info(f'[VERIFY] Verification counter updated: {document.verification_count}')
            
            # Render verification page dengan hasil verifikasi BSRE
            _logger.info(f'[VERIFY] Rendering verification page template...')
            _logger.info(f'[VERIFY] verify_result untuk template: {json.dumps(verify_result, indent=2, default=str) if verify_result else "None"}')
            _logger.info(f'[VERIFY] bsre_verification_success: {bsre_verification_success}')
            
            # Pastikan verify_result adalah dict yang bisa diakses di template
            template_verify_result = verify_result if (verify_result and isinstance(verify_result, dict)) else {}
            
            # Log khusus untuk signer field sebelum dikirim ke template
            if template_verify_result:
                _logger.info(f'[VERIFY] template_verify_result keys: {list(template_verify_result.keys())}')
                _logger.info(f'[VERIFY] template_verify_result.get("signer"): {template_verify_result.get("signer")}')
                _logger.info(f'[VERIFY] template_verify_result type: {type(template_verify_result)}')
                _logger.info(f'[VERIFY] template_verify_result["signer"] (direct access): {template_verify_result.get("signer") if "signer" in template_verify_result else "KEY NOT FOUND"}')
            
            result = request.render('sicantik_tte.verification_page', {
                'document': document,
                'bsre_verification': template_verify_result,
                'bsre_verification_success': bsre_verification_success,
            })
            _logger.info(f'[VERIFY] Template rendered successfully')
            _logger.info(f'[VERIFY] ==========================================')
            return result
            
        except Exception as e:
            _logger.error(f'[VERIFY] ==========================================')
            _logger.error(f'[VERIFY] Error verifying document {document_number}: {str(e)}', exc_info=True)
            _logger.error(f'[VERIFY] ==========================================')
            return request.render('sicantik_tte.verification_error', {
                'error': str(e),
                'document_number': document_number
            })

