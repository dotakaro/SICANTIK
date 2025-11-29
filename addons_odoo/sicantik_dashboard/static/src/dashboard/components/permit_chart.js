/** @odoo-module **/

import { Component, useRef, useState, onWillStart, onMounted, onWillUnmount, useEffect } from "@odoo/owl";
import { loadJS } from "@web/core/assets";
import { cookie } from "@web/core/browser/cookie";
import { getColor, getCustomColor } from "@web/core/colors/colors";

export class PermitChart extends Component {
    static template = "sicantik_dashboard.PermitChart";
    static props = {
        title: String,
        data: Object, // { labels: [], values: [] }
        chartType: { type: String, optional: true }, // 'bar', 'pie', 'line'
    };
    
    setup() {
        this.canvasRef = useRef("canvas");
        this.chart = null;
        this.state = useState({
            chartType: this.props.chartType || 'bar',
        });
        
        const colorScheme = cookie.get("color_scheme");
        this.gridColor = getCustomColor(colorScheme, "#d8dadd", "#3C3E4B");
        this.labelColor = getCustomColor(colorScheme, "#111827", "#E4E4E4");
        
        onWillStart(() => loadJS("/web/static/lib/Chart/Chart.js"));
        useEffect(() => {
            this.renderChart();
            return () => {
                if (this.chart) {
                    this.chart.destroy();
                }
            };
        }, () => [this.props.data, this.state.chartType]);
        onWillUnmount(() => {
            if (this.chart) {
                this.chart.destroy();
            }
        });
    }
    
    get chartConfig() {
        const { labels, values } = this.props.data;
        const colors = this.generateColors(values.length);
        
        if (this.state.chartType === 'pie') {
            return {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: this.labelColor,
                                padding: 15,
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                },
                            },
                        },
                    },
                },
            };
        } else if (this.state.chartType === 'line') {
            return {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Izin',
                        data: values,
                        borderColor: getColor(1, cookie.get("color_scheme"), "odoo"),
                        backgroundColor: getColor(1, cookie.get("color_scheme"), "odoo") + '20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: this.labelColor,
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: this.gridColor,
                            },
                            ticks: {
                                color: this.labelColor,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: this.gridColor,
                            },
                            ticks: {
                                color: this.labelColor,
                                stepSize: 1,
                            },
                        },
                    },
                },
            };
        } else {
            // Bar chart (default)
            return {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Izin',
                        data: values,
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.8', '1')),
                        borderWidth: 2,
                        borderRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    return `Jumlah: ${context.parsed.y}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: this.labelColor,
                                maxRotation: 45,
                                minRotation: 0,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: this.gridColor,
                            },
                            ticks: {
                                color: this.labelColor,
                                stepSize: 1,
                            },
                        },
                    },
                },
            };
        }
    }
    
    generateColors(count) {
        // Color palette yang lebih baik dengan variasi warna yang lebih banyak
        const colorPalette = [
            '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8',
            '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6c757d',
            '#0056b3', '#1e7e34', '#d39e00', '#bd2130', '#117a8b',
            '#5a6268', '#c82333', '#138496', '#e0a800', '#218838',
            '#0069d9', '#1c7430', '#ffb300', '#c82333', '#0c5460',
        ];
        
        const colors = [];
        for (let i = 0; i < count; i++) {
            // Gunakan modulo untuk cycle melalui palette
            const colorIndex = i % colorPalette.length;
            colors.push(colorPalette[colorIndex]);
        }
        return colors;
    }
    
    renderChart() {
        if (!this.canvasRef.el || !window.Chart) {
            return;
        }
        
        if (this.chart) {
            this.chart.destroy();
        }
        
        const config = this.chartConfig;
        this.chart = new Chart(this.canvasRef.el, config);
    }
    
    onChartTypeChange(type) {
        this.state.chartType = type;
    }
}

