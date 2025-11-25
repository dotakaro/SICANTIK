# -*- coding: utf-8 -*-

import logging
import socket

_logger = logging.getLogger(__name__)


class MinioClient:
    """
    MinIO client yang kompatibel dengan versi terbaru
    Menggunakan path-style access untuk menghindari masalah hostname dengan underscore
    """
    
    def __init__(self, endpoint, access_key, secret_key, secure=False, region=None):
        self.endpoint = endpoint
        self.access_key = access_key
        self.secret_key = secret_key
        self.secure = secure
        self.region = region
        self.client = None
        
    def _get_client(self):
        """Initialize MinIO client dengan path-style"""
        try:
            from minio import Minio
            
            # Gunakan path-style: http://endpoint/bucket/object
            # Jika endpoint sudah dalam format host:port, gunakan langsung
            if not self.endpoint.startswith(('http://', 'https://')):
                endpoint = f'http://{self.endpoint}'
            
            # Hapus protokol jika ada
            if self.endpoint.startswith('http://'):
                endpoint = self.endpoint[7:]
                secure = False
            elif self.endpoint.startswith('https://'):
                endpoint = self.endpoint[8:]
                secure = True
            
            # Hapus path setelah host:port (hanya host:port)
            if '/' in endpoint:
                endpoint = endpoint.split('/')[0]
            
            # Hapus slash di akhir
            endpoint = endpoint.rstrip('/')
            
            # WORKAROUND: MinIO Python library 7.2.18 menggunakan virtual-host style
            # Ketika endpoint mengandung underscore (contoh: minio_storage:9000)
            # Ini menyebabkan error "invalid hostname"
            # Solusi: resolve ke IP address untuk force path-style access
            if ':' in endpoint:
                host, port = endpoint.rsplit(':', 1)
                # Jika hostname mengandung underscore, resolve ke IP untuk force path-style
                if '_' in host:
                    try:
                        _logger.info(f"Resolving hostname {host} to IP address (workaround for underscore issue)")
                        ip_address = socket.gethostbyname(host)
                        endpoint = f'{ip_address}:{port}'
                        _logger.info(f"Using IP address: {endpoint}")
                    except socket.gaierror:
                        _logger.warning(f"Could not resolve {host} to IP, using original endpoint")
                        # Keep original endpoint if resolution fails
            _logger.info(f"Creating MinIO client: endpoint={endpoint}, secure={secure}, region=None")
            
            # Buat client tanpa region parameter
            # Ini memaksa MinIO menggunakan path-style access
            client = Minio(
                endpoint,
                access_key=self.access_key,
                secret_key=self.secret_key,
                secure=secure
            )
            
            return client
            
        except ImportError:
            raise Exception("Library MinIO (boto3/minio) tidak terinstall. Jalankan: pip install minio")
        except Exception as e:
            _logger.error(f"Error creating MinIO client: {str(e)}")
            raise Exception(f"Error koneksi MinIO: {str(e)}")
    
    def test_connection(self):
        """Test koneksi ke MinIO server"""
        try:
            client = self._get_client()
            
            # Test dengan list buckets
            buckets = list(client.list_buckets())
            _logger.info(f"MinIO connection test successful. Found {len(buckets)} buckets.")
            return True, "Connection successful"
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f"MinIO connection test failed: {error_msg}")
            return False, f"Connection failed: {error_msg}"
    
    def ensure_bucket_exists(self, bucket_name):
        """
        Ensure bucket exists, create if not
        
        Args:
            bucket_name (str): Nama bucket
        
        Returns:
            bool: True if bucket exists or created successfully
        
        Raises:
            Exception: If bucket creation fails
        """
        if not bucket_name:
            raise Exception("Nama bucket tidak boleh kosong")
        
        try:
            _logger.info(f"Checking bucket existence: {bucket_name}")
            client = self._get_client()
            
            # Cek bucket exists
            bucket_exists = client.bucket_exists(bucket_name)
            _logger.info(f"Bucket {bucket_name} exists: {bucket_exists}")
            
            if not bucket_exists:
                # Buat bucket tanpa region untuk menghindari validasi hostname
                _logger.info(f"Creating bucket: {bucket_name}")
                client.make_bucket(bucket_name)
                _logger.info(f"MinIO bucket created successfully: {bucket_name}")
            else:
                _logger.info(f"Bucket {bucket_name} already exists")
            
            return True
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f"Error ensuring bucket exists: {error_msg}")
            raise Exception(f"Gagal membuat atau memastikan bucket '{bucket_name}': {error_msg}")
    
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
        try:
            from io import BytesIO
            
            _logger.info(f"Starting upload to MinIO: endpoint={self.endpoint}, bucket={bucket_name}, object={object_name}")
            
            client = self._get_client()
            
            # Pastikan bucket ada (akan raise Exception jika gagal)
            self.ensure_bucket_exists(bucket_name)
            
            # Convert bytes ke file-like object
            file_stream = BytesIO(file_data)
            file_size = len(file_data)
            
            _logger.info(f"Uploading file: size={file_size} bytes, content_type={content_type}")
            
            # Upload ke MinIO
            client.put_object(
                bucket_name=bucket_name,
                object_name=object_name,
                data=file_stream,
                length=file_size,
                content_type=content_type
            )
            
            _logger.info(f"File uploaded successfully to MinIO: {bucket_name}/{object_name}")
            
            return {
                'success': True,
                'message': 'File berhasil diupload',
                'bucket': bucket_name,
                'object_name': object_name,
                'size': file_size
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f"Error uploading file to MinIO: {error_msg}")
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
        try:
            client = self._get_client()
            
            # Download dari MinIO
            response = client.get_object(bucket_name, object_name)
            file_data = response.read()
            response.close()
            response.release_conn()
            
            _logger.info(f"File downloaded from MinIO: {bucket_name}/{object_name}")
            
            return {
                'success': True,
                'message': 'File berhasil didownload',
                'data': file_data,
                'size': len(file_data)
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f"Error downloading file from MinIO: {error_msg}")
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
        try:
            client = self._get_client()
            
            # Delete dari MinIO
            client.remove_object(bucket_name, object_name)
            
            _logger.info(f"File deleted from MinIO: {bucket_name}/{object_name}")
            
            return {
                'success': True,
                'message': 'File berhasil dihapus'
            }
            
        except Exception as e:
            error_msg = str(e)
            _logger.error(f"Error deleting file from MinIO: {error_msg}")
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
            expires (int): Expiry time dalam detik (default 1 jam)
        
        Returns:
            str: Presigned URL atau False jika error
        """
        try:
            from datetime import timedelta
            
            client = self._get_client()
            
            # Generate presigned URL
            url = client.presigned_get_object(
                bucket_name=bucket_name,
                object_name=object_name,
                expires=timedelta(seconds=expires)
            )
            
            return url
            
        except Exception as e:
            _logger.error(f"Error generating presigned URL: {str(e)}")
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
        try:
            client = self._get_client()
            
            objects = client.list_objects(bucket_name, prefix=prefix, recursive=True)
            object_names = [obj.object_name for obj in objects]
            
            return object_names
            
        except Exception as e:
            _logger.error(f"Error listing objects: {str(e)}")
            return []
