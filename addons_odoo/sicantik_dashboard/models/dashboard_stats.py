# -*- coding: utf-8 -*-

import logging
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta

from odoo import models, fields, api
from odoo.tools import DEFAULT_SERVER_DATE_FORMAT

_logger = logging.getLogger(__name__)


class SicantikDashboardStats(models.TransientModel):
    """
    Model untuk komputasi statistik dashboard
    Menggunakan TransientModel karena hanya untuk komputasi, tidak perlu disimpan
    """
    _name = 'sicantik.dashboard.stats'
    _description = 'SICANTIK Dashboard Statistics'

    @api.model
    def get_permit_stats(self):
        """
        Komputasi statistik izin
        
        Returns:
            dict: Statistik izin lengkap
        """
        Permit = self.env['sicantik.permit']
        today = fields.Date.today()
        
        # Total per status
        total_active = Permit.search_count([('status', '=', 'active')])
        total_expired = Permit.search_count([('status', '=', 'expired')])
        total_draft = Permit.search_count([('status', '=', 'draft')])
        total_renewed = Permit.search_count([('status', '=', 'renewed')])
        
        # Per kategori
        try:
            permits_by_category = Permit._read_group(
                domain=[('status', '=', 'active')],
                groupby=['permit_type_name'],
                aggregates=['__count'],
                order='__count desc',
                limit=20
            )
            by_category = [
                {
                    'name': (permit_type_name or 'Tidak Diketahui') if isinstance(permit_type_name, str) else 'Tidak Diketahui',
                    'count': count
                }
                for permit_type_name, count in permits_by_category
            ]
        except Exception as e:
            _logger.warning(f"Error getting permits by category: {str(e)}")
            # Fallback: gunakan search biasa
            permits = Permit.search([('status', '=', 'active')])
            category_counts = {}
            for permit in permits:
                category = permit.permit_type_name or 'Tidak Diketahui'
                category_counts[category] = category_counts.get(category, 0) + 1
            by_category = [
                {'name': name, 'count': count}
                for name, count in sorted(category_counts.items(), key=lambda x: x[1], reverse=True)[:20]
            ]
        
        # Per tahun (5 tahun terakhir)
        # Gunakan create_date jika issue_date tidak ada atau null
        current_year = today.year
        by_year = []
        for year in range(current_year - 4, current_year + 1):
            # Coba dengan issue_date dulu, jika tidak ada gunakan create_date
            year_start = fields.Date.to_date(f'{year}-01-01')
            year_end = fields.Date.to_date(f'{year + 1}-01-01')
            
            # Cari dengan issue_date jika ada
            count_with_issue_date = Permit.search_count([
                ('status', '=', 'active'),
                ('issue_date', '>=', year_start),
                ('issue_date', '<', year_end),
            ])
            
            # Cari dengan create_date untuk yang tidak punya issue_date
            count_with_create_date = Permit.search_count([
                ('status', '=', 'active'),
                '|',
                ('issue_date', '=', False),
                ('issue_date', '=', None),
                ('create_date', '>=', f'{year}-01-01 00:00:00'),
                ('create_date', '<', f'{year + 1}-01-01 00:00:00'),
            ])
            
            # Total: yang punya issue_date + yang tidak punya issue_date tapi create_date di tahun tersebut
            total_count = count_with_issue_date + count_with_create_date
            
            by_year.append({
                'year': year,
                'count': total_count
            })
        
        # Expiry breakdown
        # Expired (sudah kadaluarsa)
        expired_count = Permit.search_count([
            ('status', '=', 'expired'),
            ('expiry_date', '<', today)
        ])
        
        # Hari ini
        today_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '=', today)
        ])
        
        # Minggu ini (sisa hari dalam minggu ini)
        week_end = today + timedelta(days=(6 - today.weekday()))
        this_week_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '>', today),
            ('expiry_date', '<=', week_end)
        ])
        
        # Bulan ini
        month_end = today.replace(day=1) + relativedelta(months=1) - timedelta(days=1)
        this_month_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '>', today),
            ('expiry_date', '<=', month_end)
        ])
        
        # 3 bulan ke depan
        three_months = today + relativedelta(months=3)
        next_3_months_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '>', today),
            ('expiry_date', '<=', three_months)
        ])
        
        # 6 bulan ke depan
        six_months = today + relativedelta(months=6)
        next_6_months_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '>', today),
            ('expiry_date', '<=', six_months)
        ])
        
        # Tahun ini
        year_end = today.replace(month=12, day=31)
        this_year_count = Permit.search_count([
            ('status', '=', 'active'),
            ('expiry_date', '>', today),
            ('expiry_date', '<=', year_end)
        ])
        
        return {
            'total_active': total_active,
            'total_expired': total_expired,
            'total_draft': total_draft,
            'total_renewed': total_renewed,
            'by_category': by_category,
            'by_year': by_year,
            'expiry_breakdown': {
                'expired': expired_count,
                'today': today_count,
                'this_week': this_week_count,
                'this_month': this_month_count,
                'next_3_months': next_3_months_count,
                'next_6_months': next_6_months_count,
                'this_year': this_year_count,
            }
        }

    @api.model
    def get_document_stats(self):
        """
        Komputasi statistik dokumen TTE
        
        Returns:
            dict: Statistik dokumen lengkap
        """
        Document = self.env['sicantik.document']
        today = fields.Date.today()
        
        # Total per status
        total_signed = Document.search_count([('state', '=', 'signed')])
        pending_signature = Document.search_count([('state', '=', 'pending_signature')])
        verified = Document.search_count([('state', '=', 'verified')])
        cancelled = Document.search_count([('state', '=', 'cancelled')])
        
        # Monthly trend (12 bulan terakhir)
        monthly_trend = []
        for i in range(11, -1, -1):
            month_start = today.replace(day=1) - relativedelta(months=i)
            month_end = month_start + relativedelta(months=1) - timedelta(days=1)
            
            # Convert date to datetime for signature_date field (Datetime field)
            month_start_dt = datetime.combine(month_start, datetime.min.time())
            month_end_dt = datetime.combine(month_end, datetime.max.time())
            
            count = Document.search_count([
                ('state', '=', 'signed'),
                ('signature_date', '>=', month_start_dt),
                ('signature_date', '<=', month_end_dt)
            ])
            
            monthly_trend.append({
                'month': month_start.strftime('%Y-%m'),
                'count': count
            })
        
        return {
            'total_signed': total_signed,
            'pending_signature': pending_signature,
            'verified': verified,
            'cancelled': cancelled,
            'monthly_trend': monthly_trend,
        }

    @api.model
    def get_whatsapp_stats(self):
        """
        Komputasi statistik WhatsApp
        
        Returns:
            dict: Statistik WhatsApp lengkap
        """
        Partner = self.env['res.partner']
        MessageLog = self.env['sicantik.whatsapp.message.log']
        today = fields.Date.today()
        now = fields.Datetime.now()
        
        # Total kontak dengan WhatsApp
        total_contacts = Partner.search_count([
            '|',
            ('whatsapp_number', '!=', False),
            ('phone', '!=', False)
        ])
        
        # Opt-in aktif
        opt_in_active = Partner.search_count([
            ('whatsapp_opt_in', '=', True)
        ])
        
        # Opt-in rate
        opt_in_rate = (opt_in_active / total_contacts * 100) if total_contacts > 0 else 0.0
        
        # Pesan terkirim hari ini
        today_start = datetime.combine(today, datetime.min.time())
        today_end = datetime.combine(today, datetime.max.time())
        messages_today = MessageLog.search_count([
            ('state', '=', 'sent'),
            ('sent_date', '>=', today_start),
            ('sent_date', '<=', today_end)
        ])
        
        # Pesan terkirim minggu ini
        week_start = today - timedelta(days=today.weekday())
        week_start_dt = datetime.combine(week_start, datetime.min.time())
        messages_this_week = MessageLog.search_count([
            ('state', '=', 'sent'),
            ('sent_date', '>=', week_start_dt),
            ('sent_date', '<=', now)
        ])
        
        # Pesan terkirim bulan ini
        month_start = today.replace(day=1)
        month_start_dt = datetime.combine(month_start, datetime.min.time())
        messages_this_month = MessageLog.search_count([
            ('state', '=', 'sent'),
            ('sent_date', '>=', month_start_dt),
            ('sent_date', '<=', now)
        ])
        
        # Pesan terkirim tahun ini
        year_start = today.replace(month=1, day=1)
        year_start_dt = datetime.combine(year_start, datetime.min.time())
        messages_this_year = MessageLog.search_count([
            ('state', '=', 'sent'),
            ('sent_date', '>=', year_start_dt),
            ('sent_date', '<=', now)
        ])
        
        # Pesan gagal
        messages_failed = MessageLog.search_count([
            ('state', '=', 'failed')
        ])
        
        # Total pesan terkirim (untuk menghitung failure rate)
        total_sent = MessageLog.search_count([
            ('state', 'in', ['sent', 'delivered', 'read'])
        ])
        total_messages = total_sent + messages_failed
        failure_rate = (messages_failed / total_messages * 100) if total_messages > 0 else 0.0
        
        # Per provider
        by_provider = {}
        for provider_type in ['meta', 'fonnte', 'watzap']:
            count = MessageLog.search_count([
                ('provider_type', '=', provider_type),
                ('state', 'in', ['sent', 'delivered', 'read'])
            ])
            by_provider[provider_type] = count
        
        return {
            'total_contacts': total_contacts,
            'opt_in_active': opt_in_active,
            'opt_in_rate': round(opt_in_rate, 2),
            'messages_today': messages_today,
            'messages_this_week': messages_this_week,
            'messages_this_month': messages_this_month,
            'messages_this_year': messages_this_year,
            'messages_failed': messages_failed,
            'failure_rate': round(failure_rate, 2),
            'by_provider': by_provider,
        }

