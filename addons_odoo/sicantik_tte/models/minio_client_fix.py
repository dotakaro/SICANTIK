# -*- coding: utf-8 -*-

import logging
import socket

_logger = logging.getLogger(__name__)


class MinioClient:
    """
    MinIO client yang kompatibel dengan versi terbaru
    Menggunakan path-style access untuk menghindari masalah hostname
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
            
            # Create client WITHOUT region parameter
            # This forces MinIO to use path-style access instead of virtual-host style
            # Path-style: http://endpoint/bucket/object
            # Virtual-host: http://bucket.endpoint/object (causes invalid hostname error)
            client = Minio(
                endpoint,
                access_key=self.access_key,
                secret_key=self.secret_key,
                secure=secure,
                # Explicitly NOT setting region to force path-style access
            )
            
            return client
            
        except ImportError:
            raise Exception("Library MinIO (boto3/minio) tidak terinstall. Jalankan: pip install minio")
        except Exception as e:
            _logger.error(f"Error creating MinIO client: {str(e)}")
            _logger.error(f"Endpoint used: {self.endpoint}, Secure: {self.secure}")
            raise Exception(f"Error koneksi MinIO: {str(e)}")
