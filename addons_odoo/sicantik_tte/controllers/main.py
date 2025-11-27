# -*- coding: utf-8 -*-

from odoo import http, fields
from odoo.http import request, Response
from odoo.exceptions import UserError, AccessError
import logging
import base64

_logger = logging.getLogger(__name__)


class SicantikTTEController(http.Controller):
    """
    Controller untuk handle download dokumen dari MinIO dan verifikasi publik
    """
    
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
        '/sicantik/tte/verify/<string:document_number>',
        '/sicantik/tte/verify/<string:document_number>/',
    ], type='http', auth='public', methods=['GET'], csrf=False, website=True)
    def verify_document(self, document_number, **kwargs):
        """
        Public endpoint untuk verifikasi dokumen
        
        Args:
            document_number: Nomor dokumen
        
        Returns:
            Halaman verifikasi dokumen
        """
        try:
            _logger.info(f'[VERIFY] ==========================================')
            _logger.info(f'[VERIFY] Request untuk verifikasi dokumen: {document_number}')
            _logger.info(f'[VERIFY] URL: {request.httprequest.url}')
            _logger.info(f'[VERIFY] Method: {request.httprequest.method}')
            _logger.info(f'[VERIFY] User Agent: {request.httprequest.headers.get("User-Agent", "N/A")}')
            
            # Normalize document_number (remove leading/trailing spaces)
            document_number = document_number.strip()
            
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
            
            # Update verification counter
            document.write({
                'verification_count': document.verification_count + 1,
                'last_verified_date': fields.Datetime.now()
            })
            
            _logger.info(f'[VERIFY] Verification counter updated: {document.verification_count}')
            
            # Render verification page
            _logger.info(f'[VERIFY] Rendering verification page template...')
            result = request.render('sicantik_tte.verification_page', {
                'document': document,
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

