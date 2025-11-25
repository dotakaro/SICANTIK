# -*- coding: utf-8 -*-

import base64
import logging

from odoo import api, fields, models
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)


class DocumentUploadWizard(models.TransientModel):
    """
    Wizard untuk upload dokumen PDF ke MinIO
    """

    _name = "document.upload.wizard"
    _description = "Wizard Upload Dokumen"

    # Document Selection
    permit_id = fields.Many2one(
        "sicantik.permit",
        string="Pilih Izin",
        required=True,
        domain=[("status", "=", "active")],
        help="Pilih izin yang akan diupload dokumennya",
    )
    # Related fields untuk display info lengkap
    applicant_name = fields.Char(
        related="permit_id.applicant_name", string="Nama Pemohon", readonly=True
    )
    permit_number = fields.Char(
        related="permit_id.permit_number", string="Nomor Izin", readonly=True
    )
    permit_type_id = fields.Many2one(
        related="permit_id.permit_type_id", string="Jenis Izin", readonly=True
    )
    permit_type_name = fields.Char(
        related="permit_id.permit_type_name", string="Nama Jenis Izin", readonly=True
    )

    # File Upload
    file_data = fields.Binary(
        string="File PDF", required=True, help="Upload file PDF dokumen izin"
    )
    filename = fields.Char(string="Nama File", required=True)

    # Document Info
    document_name = fields.Char(
        string="Nama Dokumen", required=True, help="Nama dokumen untuk identifikasi"
    )
    notes = fields.Text(string="Catatan", help="Catatan tambahan untuk dokumen")

    # Options
    auto_request_signature = fields.Boolean(
        string="Langsung Minta Tanda Tangan",
        default=False,
        help="Otomatis create workflow tanda tangan setelah upload",
    )

    @api.onchange("permit_id")
    def _onchange_permit_id(self):
        """Auto-fill document name when permit is selected"""
        if self.permit_id:
            # Format: "nama pemohon - nomor izin - jenis izin"
            parts = []
            
            # Nama pemohon (akses langsung dari permit_id untuk memastikan data terbaru)
            applicant_name = self.permit_id.applicant_name or ""
            if applicant_name.strip():
                parts.append(applicant_name.strip())
            
            # Nomor izin
            permit_number = self.permit_id.permit_number or ""
            if permit_number.strip():
                parts.append(permit_number.strip())
            
            # Jenis izin (gunakan permit_type_name atau name dari permit_type_id)
            permit_type_name = self.permit_id.permit_type_name or ""
            if not permit_type_name and self.permit_id.permit_type_id:
                permit_type_name = self.permit_id.permit_type_id.name or ""
            if permit_type_name.strip():
                parts.append(permit_type_name.strip())
            
            # Gabungkan dengan separator " - "
            if parts:
                self.document_name = " - ".join(parts)
            else:
                self.document_name = ""

    @api.onchange("filename")
    def _onchange_filename(self):
        """Validate PDF file"""
        if self.filename and not self.filename.lower().endswith(".pdf"):
            return {
                "warning": {
                    "title": "Peringatan",
                    "message": "File harus berformat PDF",
                }
            }

    def action_upload(self):
        """Upload dokumen ke MinIO"""
        self.ensure_one()

        # Validate file
        if not self.filename or not self.filename.lower().endswith(".pdf"):
            raise UserError("File harus berformat PDF")

        if not self.file_data:
            raise UserError("File belum dipilih")

        try:
            # Handle file_data yang bisa berupa string base64 atau list [filename, base64_data]
            file_data_str = self.file_data
            if isinstance(self.file_data, list):
                # Jika file_data adalah list, ambil elemen kedua (base64 data)
                if len(self.file_data) >= 2:
                    file_data_str = self.file_data[1]
                elif len(self.file_data) == 1:
                    file_data_str = self.file_data[0]
                else:
                    raise UserError("Format file data tidak valid")

            # Decode file data
            try:
                file_binary = base64.b64decode(file_data_str)
            except Exception as decode_error:
                _logger.error(f"Error decoding file data: {str(decode_error)}")
                raise UserError(f"Error decode file: {str(decode_error)}")

            # Create document record
            document = self.env["sicantik.document"].create(
                {
                    "name": self.document_name,
                    "permit_id": self.permit_id.id,
                    "notes": self.notes,
                    "state": "draft",
                }
            )

            # Upload to MinIO
            result = document.action_upload_to_minio(
                file_data=file_binary, filename=self.filename
            )

            # Ensure result is dict with safe access
            if not isinstance(result, dict):
                _logger.error(f"Unexpected result type: {type(result)}, value: {result}")
                document.unlink()
                raise UserError("Upload gagal: Format response tidak valid")

            if result.get("success"):
                # Auto request signature if option enabled
                if self.auto_request_signature:
                    document.action_request_signature()

                _logger.info(
                    f"Document uploaded successfully: {document.document_number}"
                )

                # Return action to open the uploaded document
                return {
                    "type": "ir.actions.act_window",
                    "name": "Dokumen Terupload",
                    "res_model": "sicantik.document",
                    "res_id": document.id,
                    "view_mode": "form",
                    "target": "current",
                }
            else:
                # Delete document if upload failed
                document.unlink()
                error_message = result.get("message", "Upload gagal: Tidak ada pesan error")
                raise UserError(f"Upload gagal: {error_message}")

        except UserError:
            # Re-raise UserError as-is
            raise
        except Exception as e:
            _logger.error(f"Error uploading document: {str(e)}", exc_info=True)
            raise UserError(f"Error upload dokumen: {str(e)}")

    def action_cancel(self):
        """Cancel upload"""
        return {"type": "ir.actions.act_window_close"}
