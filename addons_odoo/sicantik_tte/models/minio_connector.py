# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import UserError, ValidationError
import logging

_logger = logging.getLogger(__name__)


class MinioConnector(models.Model):
    """
    Connector untuk MinIO S3-compatible object storage
    """
    _name = 'minio.connector'
    _description = 'MinIO Storage Connector'
    _rec_name = 'name'
    
    name = fields.Char(
        string='Nama Konfigurasi',
        required=True,
        default='MinIO Storage'
    )
    active = fields.Boolean(
        string='Aktif',
        default=True
    )
    
    # MinIO Configuration
    endpoint = fields.Char(
        string='Endpoint',
        required=True,
        default='localhost:9000',
        help='MinIO server endpoint (host:port)'
    )
    access_key = fields.Char(
        string='Access Key',
        required=True,
        help='MinIO access key'
    )
    secret_key = fields.Char(
        string='Secret Key',
        required=True,
        help='MinIO secret key'
    )
    secure = fields.Boolean(
        string='Use HTTPS',
        default=False,
        help='Use secure connection (HTTPS)'
    )
    region = fields.Char(
        string='Region',
        default='us-east-1',
        help='MinIO region'
    )
    
    # Default Bucket
    default_bucket = fields.Char(
        string='Default Bucket',
        default='sicantik-documents',
        help='Default bucket untuk upload dokumen'
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
    total_uploads = fields.Integer(
        string='Total Upload',
        default=0,
        readonly=True
    )
    total_downloads = fields.Integer(
        string='Total Download',
        default=0,
        readonly=True
    )
    total_storage_used = fields.Float(
        string='Storage Terpakai (MB)',
        default=0.0,
        readonly=True,
        help='Total storage yang terpakai dalam MB'
    )
    
    @api.constrains('active')
    def _check_one_active_config(self):
        """Ensure only one active MinIO configuration"""
        if self.active:
            active_configs = self.search([('active', '=', True), ('id', '!=', self.id)])
            if active_configs:
                raise ValidationError('Hanya satu konfigurasi MinIO yang dapat aktif pada satu waktu')
    
    def _get_minio_client(self):
        """
        Get MinIO client instance
        
        Returns:
            Minio: MinIO client object
        """
        self.ensure_one()
        
        try:
            from minio import Minio
            
            client = Minio(
                self.endpoint,
                access_key=self.access_key,
                secret_key=self.secret_key,
                secure=self.secure,
                region=self.region
            )
            
            return client
            
        except ImportError:
            raise UserError('Library MinIO (boto3/minio) tidak terinstall. Jalankan: pip install minio')
        except Exception as e:
            _logger.error(f'Error creating MinIO client: {str(e)}')
            raise UserError(f'Error koneksi MinIO: {str(e)}')
    
    def action_test_connection(self):
        """Test koneksi ke MinIO server"""
        self.ensure_one()
        
        try:
            client = self._get_minio_client()
            
            # Test connection by listing buckets
            buckets = list(client.list_buckets())
            
            self.write({
                'connection_status': 'connected',
                'last_error': False
            })
            
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Koneksi Berhasil',
                    'message': f'Berhasil terhubung ke MinIO. Ditemukan {len(buckets)} bucket.',
                    'type': 'success',
                    'sticky': False,
                }
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'MinIO connection test failed: {error_msg}')
            
            self.write({
                'connection_status': 'error',
                'last_error': error_msg
            })
            
            raise UserError(f'Koneksi gagal: {error_msg}')
    
    def ensure_bucket_exists(self, bucket_name):
        """
        Ensure bucket exists, create if not
        
        Args:
            bucket_name (str): Nama bucket
        
        Returns:
            bool: True if bucket exists or created successfully
        """
        self.ensure_one()
        
        try:
            client = self._get_minio_client()
            
            # Check if bucket exists
            if not client.bucket_exists(bucket_name):
                # Create bucket
                client.make_bucket(bucket_name)
                _logger.info(f'MinIO bucket created: {bucket_name}')
            
            return True
            
        except Exception as e:
            _logger.error(f'Error ensuring bucket exists: {str(e)}')
            return False
    
    def upload_file(self, bucket_name, object_name, file_data, content_type='application/pdf'):
        """
        Upload file ke MinIO
        
        Args:
            bucket_name (str): Nama bucket
            object_name (str): Nama object/path di MinIO
            file_data (bytes): Binary data file
            content_type (str): Content type file
        
        Returns:
            dict: Result with success status and message
        """
        self.ensure_one()
        
        try:
            from io import BytesIO
            
            client = self._get_minio_client()
            
            # Ensure bucket exists
            self.ensure_bucket_exists(bucket_name)
            
            # Convert bytes to file-like object
            file_stream = BytesIO(file_data)
            file_size = len(file_data)
            
            # Upload to MinIO
            client.put_object(
                bucket_name=bucket_name,
                object_name=object_name,
                data=file_stream,
                length=file_size,
                content_type=content_type
            )
            
            # Update statistics
            self.write({
                'total_uploads': self.total_uploads + 1,
                'total_storage_used': self.total_storage_used + (file_size / 1024 / 1024)  # Convert to MB
            })
            
            _logger.info(f'File uploaded to MinIO: {bucket_name}/{object_name}')
            
            return {
                'success': True,
                'message': 'File berhasil diupload',
                'bucket': bucket_name,
                'object_name': object_name,
                'size': file_size
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'Error uploading file to MinIO: {error_msg}')
            
            return {
                'success': False,
                'message': f'Upload gagal: {error_msg}'
            }
    
    def download_file(self, bucket_name, object_name):
        """
        Download file dari MinIO
        
        Args:
            bucket_name (str): Nama bucket
            object_name (str): Nama object/path di MinIO
        
        Returns:
            dict: Result with success status, data, and message
        """
        self.ensure_one()
        
        try:
            client = self._get_minio_client()
            
            # Download from MinIO
            response = client.get_object(bucket_name, object_name)
            file_data = response.read()
            response.close()
            response.release_conn()
            
            # Update statistics
            self.write({
                'total_downloads': self.total_downloads + 1
            })
            
            _logger.info(f'File downloaded from MinIO: {bucket_name}/{object_name}')
            
            return {
                'success': True,
                'message': 'File berhasil didownload',
                'data': file_data,
                'size': len(file_data)
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'Error downloading file from MinIO: {error_msg}')
            
            return {
                'success': False,
                'message': f'Download gagal: {error_msg}',
                'data': None
            }
    
    def delete_file(self, bucket_name, object_name):
        """
        Delete file dari MinIO
        
        Args:
            bucket_name (str): Nama bucket
            object_name (str): Nama object/path di MinIO
        
        Returns:
            dict: Result with success status and message
        """
        self.ensure_one()
        
        try:
            client = self._get_minio_client()
            
            # Delete from MinIO
            client.remove_object(bucket_name, object_name)
            
            _logger.info(f'File deleted from MinIO: {bucket_name}/{object_name}')
            
            return {
                'success': True,
                'message': 'File berhasil dihapus'
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f'Error deleting file from MinIO: {error_msg}')
            
            return {
                'success': False,
                'message': f'Delete gagal: {error_msg}'
            }
    
    def get_file_url(self, bucket_name, object_name, expires=3600):
        """
        Get presigned URL untuk download file
        
        Args:
            bucket_name (str): Nama bucket
            object_name (str): Nama object/path di MinIO
            expires (int): Expiry time dalam detik (default 1 hour)
        
        Returns:
            str: Presigned URL atau False jika error
        """
        self.ensure_one()
        
        try:
            from datetime import timedelta
            
            client = self._get_minio_client()
            
            # Generate presigned URL
            url = client.presigned_get_object(
                bucket_name=bucket_name,
                object_name=object_name,
                expires=timedelta(seconds=expires)
            )
            
            return url
            
        except Exception as e:
            _logger.error(f'Error generating presigned URL: {str(e)}')
            return False
    
    def list_objects(self, bucket_name, prefix=''):
        """
        List objects dalam bucket
        
        Args:
            bucket_name (str): Nama bucket
            prefix (str): Prefix untuk filter objects
        
        Returns:
            list: List of object names
        """
        self.ensure_one()
        
        try:
            client = self._get_minio_client()
            
            objects = client.list_objects(bucket_name, prefix=prefix, recursive=True)
            object_names = [obj.object_name for obj in objects]
            
            return object_names
            
        except Exception as e:
            _logger.error(f'Error listing objects: {str(e)}')
            return []
    
    def action_view_statistics(self):
        """View storage statistics"""
        self.ensure_one()
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Statistik MinIO Storage',
            'res_model': 'minio.connector',
            'res_id': self.id,
            'view_mode': 'form',
            'target': 'current',
        }

