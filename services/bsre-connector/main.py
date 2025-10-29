"""
SICANTIK Companion App - BSRE Connector Service
TTE BSRE Integration untuk Digital Signature
"""

from fastapi import FastAPI, HTTPException, UploadFile, File, Form
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import Optional, List
import os
import logging
import requests
import json
from datetime import datetime
import qrcode
from io import BytesIO
import base64

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/app/logs/bsre_connector.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

app = FastAPI(
    title="SICANTIK BSRE Connector",
    description="TTE BSRE Integration Service untuk Digital Signature",
    version="1.0.0"
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # In production, specify allowed origins
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Environment variables
BSRE_API_URL = os.getenv("BSRE_API_URL", "https://api-sandbox.bsre.id/v1")
BSRE_CLIENT_ID = os.getenv("BSRE_CLIENT_ID", "demo_client_id")
BSRE_CLIENT_SECRET = os.getenv("BSRE_CLIENT_SECRET", "demo_client_secret")
BSRE_ENVIRONMENT = os.getenv("BSRE_ENVIRONMENT", "sandbox")

# Pydantic models
class DocumentSignRequest(BaseModel):
    document_id: str
    document_name: str
    signer_name: str
    signer_nik: str
    signer_position: str
    signature_reason: str
    signature_location: str

class SignatureResponse(BaseModel):
    success: bool
    message: str
    document_id: Optional[str] = None
    signed_document_url: Optional[str] = None
    qr_code: Optional[str] = None

class VerificationRequest(BaseModel):
    document_id: str
    qr_code_data: Optional[str] = None

class VerificationResponse(BaseModel):
    valid: bool
    document_info: Optional[dict] = None
    signature_info: Optional[dict] = None
    message: str

@app.get("/")
async def root():
    """Root endpoint"""
    return {
        "service": "SICANTIK BSRE Connector",
        "version": "1.0.0",
        "status": "running",
        "environment": BSRE_ENVIRONMENT
    }

@app.get("/health")
async def health_check():
    """Health check endpoint"""
    try:
        # Test BSRE API connectivity (if not in demo mode)
        if BSRE_CLIENT_ID != "demo_client_id":
            response = requests.get(f"{BSRE_API_URL}/status", timeout=5)
            bsre_status = response.status_code == 200
        else:
            bsre_status = True  # Demo mode
        
        return {
            "status": "healthy",
            "timestamp": datetime.now().isoformat(),
            "bsre_connectivity": bsre_status,
            "environment": BSRE_ENVIRONMENT
        }
    except Exception as e:
        logger.error(f"Health check failed: {str(e)}")
        raise HTTPException(status_code=503, detail="Service unhealthy")

@app.post("/sign-document", response_model=SignatureResponse)
async def sign_document(
    file: UploadFile = File(...),
    document_id: str = Form(...),
    document_name: str = Form(...),
    signer_name: str = Form(...),
    signer_nik: str = Form(...),
    signer_position: str = Form(...),
    signature_reason: str = Form(...),
    signature_location: str = Form(...)
):
    """Sign document using BSRE TTE"""
    try:
        logger.info(f"Signing document: {document_id} - {document_name}")
        
        # Read uploaded file
        file_content = await file.read()
        
        # For demo purposes, simulate signing process
        if BSRE_CLIENT_ID == "demo_client_id":
            # Demo mode - simulate successful signing
            qr_data = f"SICANTIK_VERIFY:{document_id}:{datetime.now().isoformat()}"
            qr_code_base64 = generate_qr_code(qr_data)
            
            return SignatureResponse(
                success=True,
                message="Document berhasil ditandatangani (Demo Mode)",
                document_id=document_id,
                signed_document_url=f"/download/{document_id}",
                qr_code=qr_code_base64
            )
        
        # Real BSRE integration would go here
        # TODO: Implement actual BSRE API calls
        
        return SignatureResponse(
            success=False,
            message="BSRE integration belum diimplementasi"
        )
        
    except Exception as e:
        logger.error(f"Error signing document {document_id}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Gagal menandatangani dokumen: {str(e)}")

@app.post("/verify-document", response_model=VerificationResponse)
async def verify_document(request: VerificationRequest):
    """Verify document signature"""
    try:
        logger.info(f"Verifying document: {request.document_id}")
        
        # Demo verification
        if BSRE_CLIENT_ID == "demo_client_id":
            return VerificationResponse(
                valid=True,
                document_info={
                    "document_id": request.document_id,
                    "document_name": f"Dokumen_{request.document_id}.pdf",
                    "signed_date": datetime.now().isoformat(),
                    "status": "valid"
                },
                signature_info={
                    "signer_name": "Demo Signer",
                    "signer_position": "Demo Position",
                    "signature_date": datetime.now().isoformat(),
                    "certificate_valid": True
                },
                message="Dokumen valid (Demo Mode)"
            )
        
        # Real BSRE verification would go here
        # TODO: Implement actual BSRE verification
        
        return VerificationResponse(
            valid=False,
            message="BSRE verification belum diimplementasi"
        )
        
    except Exception as e:
        logger.error(f"Error verifying document {request.document_id}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Gagal memverifikasi dokumen: {str(e)}")

@app.get("/generate-qr/{document_id}")
async def generate_qr_endpoint(document_id: str):
    """Generate QR code for document verification"""
    try:
        qr_data = f"SICANTIK_VERIFY:{document_id}:{datetime.now().isoformat()}"
        qr_code_base64 = generate_qr_code(qr_data)
        
        return {
            "document_id": document_id,
            "qr_code": qr_code_base64,
            "qr_data": qr_data
        }
        
    except Exception as e:
        logger.error(f"Error generating QR code for {document_id}: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Gagal generate QR code: {str(e)}")

@app.get("/status")
async def service_status():
    """Get service status and configuration"""
    return {
        "service": "BSRE Connector",
        "version": "1.0.0",
        "environment": BSRE_ENVIRONMENT,
        "bsre_api_url": BSRE_API_URL,
        "client_configured": BSRE_CLIENT_ID != "demo_client_id",
        "uptime": datetime.now().isoformat()
    }

def generate_qr_code(data: str) -> str:
    """Generate QR code and return as base64 string"""
    qr = qrcode.QRCode(
        version=1,
        error_correction=qrcode.constants.ERROR_CORRECT_L,
        box_size=10,
        border=4,
    )
    qr.add_data(data)
    qr.make(fit=True)
    
    img = qr.make_image(fill_color="black", back_color="white")
    
    # Convert to base64
    buffer = BytesIO()
    img.save(buffer, format='PNG')
    img_str = base64.b64encode(buffer.getvalue()).decode()
    
    return f"data:image/png;base64,{img_str}"

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
