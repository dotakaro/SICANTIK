/** @odoo-module **/

import { Component, useState, useRef, onWillStart, onMounted, onWillUnmount } from "@odoo/owl";
import { rpc } from "@web/core/network/rpc";
import { _t } from "@web/core/l10n/translation";
import { PermitChart } from "./components/permit_chart";

export class SicantikDashboard extends Component {
    static template = "sicantik_dashboard.Dashboard";
    static components = {
        PermitChart,
    };
    
    setup() {
        this.state = useState({
            loading: true,
            stats: null,
            error: null,
            isFullscreen: false,
            selectedYear: new Date().getFullYear(), // Default tahun saat ini
        });
        
        this.dashboardRef = useRef("dashboard");
        this.refreshInterval = null;
        
        onWillStart(() => this.loadStats());
        onMounted(() => {
            // Auto-refresh setiap 5 menit
            this.refreshInterval = setInterval(() => {
                this.loadStats();
            }, 5 * 60 * 1000);
            
            // Listen untuk fullscreen change events
            document.addEventListener('fullscreenchange', () => {
                this.state.isFullscreen = !!document.fullscreenElement;
            });
            document.addEventListener('webkitfullscreenchange', () => {
                this.state.isFullscreen = !!document.webkitFullscreenElement;
            });
            document.addEventListener('mozfullscreenchange', () => {
                this.state.isFullscreen = !!document.mozFullScreenElement;
            });
            document.addEventListener('MSFullscreenChange', () => {
                this.state.isFullscreen = !!document.msFullscreenElement;
            });
        });
        onWillUnmount(() => {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        });
    }
    
    async loadStats() {
        this.state.loading = true;
        this.state.error = null;
        
        try {
            const result = await rpc("/sicantik/dashboard/stats", {});
            
            if (result.success) {
                this.state.stats = {
                    permit_stats: result.permit_stats,
                    document_stats: result.document_stats,
                    whatsapp_stats: result.whatsapp_stats,
                };
            } else {
                this.state.error = result.error || "Terjadi kesalahan yang tidak diketahui";
            }
        } catch (error) {
            this.state.error = error.message || "Gagal memuat statistik dashboard";
            console.error("Dashboard stats error:", error);
        } finally {
            this.state.loading = false;
        }
    }
    
    onRefresh() {
        this.loadStats();
    }
    
    formatNumber(num) {
        if (num === null || num === undefined) return "0";
        return num.toLocaleString('id-ID');
    }
    
    formatPercentage(num) {
        if (num === null || num === undefined) return "0%";
        return `${parseFloat(num).toFixed(2)}%`;
    }
    
    toggleFullscreen() {
        const element = this.dashboardRef.el;
        
        if (!element) {
            console.warn("Dashboard element not found");
            return;
        }
        
        if (!document.fullscreenElement && 
            !document.webkitFullscreenElement && 
            !document.mozFullScreenElement &&
            !document.msFullscreenElement) {
            // Enter fullscreen
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
    
    get categoryChartData() {
        if (!this.state.stats || !this.state.stats.permit_stats) {
            return { labels: [], values: [] };
        }
        const byCategory = this.state.stats.permit_stats.by_category || [];
        return {
            labels: byCategory.map(item => item.name),
            values: byCategory.map(item => item.count),
        };
    }
    
    get yearlyChartData() {
        if (!this.state.stats || !this.state.stats.permit_stats) {
            return { labels: [], values: [] };
        }
        const byYear = this.state.stats.permit_stats.by_year || [];
        
        // Filter berdasarkan tahun yang dipilih (tampilkan 5 tahun: 2 tahun sebelum, tahun terpilih, 2 tahun setelah)
        const selectedYear = this.state.selectedYear;
        const startYear = selectedYear - 2;
        const endYear = selectedYear + 2;
        
        const filteredYears = byYear.filter(item => 
            item.year >= startYear && item.year <= endYear
        );
        
        return {
            labels: filteredYears.map(item => item.year.toString()),
            values: filteredYears.map(item => item.count),
        };
    }
    
    get availableYears() {
        if (!this.state.stats || !this.state.stats.permit_stats) {
            return [];
        }
        const byYear = this.state.stats.permit_stats.by_year || [];
        return byYear.map(item => item.year).sort((a, b) => b - a); // Sort descending
    }
    
    onYearChange(event) {
        this.state.selectedYear = parseInt(event.target.value);
    }
}

