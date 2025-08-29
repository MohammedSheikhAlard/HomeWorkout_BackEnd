class Dashboard {
    constructor() {
        this.init();
    }

    init() {
        this.fetchUsersCount();
        this.fetchActivePlansCount();
        this.initChart();
    }

    async fetchUsersCount() {
        try {
            const response = await fetch('/api/user/count');
            const data = await response.json();
            const el = document.getElementById('users-count');
            if (data && data.data && typeof data.data.count !== 'undefined') {
                el.textContent = data.data.count;
            }
        } catch (error) {
            console.error('Error fetching users count:', error);
        }
    }

    async fetchActivePlansCount() {
        try {
            const response = await fetch('/api/plan/activeCount');
            const data = await response.json();
            const el = document.getElementById('plans-count');
            if (data && data.data && typeof data.data.count !== 'undefined') {
                el.textContent = data.data.count;
            }
        } catch (error) {
            console.error('Error fetching active plans count:', error);
        }
    }



    async initChart() {
        try {
            const response = await fetch('/api/user/byLevelCounts');
            const res = await response.json();
            const data = (res && res.data) ? res.data : { counts: {}, labels: [] };
            this.renderChart(data.counts, data.labels);
            this.updateLevelsInfo(data.labels);
            this.updateLegend(data.counts);
        } catch (error) {
            console.error('Error initializing chart:', error);
        }
    }

    renderChart(counts, labels) {
        const canvas = document.getElementById('users-by-level-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const values = Object.values(counts);
        const levelNames = Object.keys(counts);
        
        // Generate colors dynamically
        const colors = this.generateColors(levelNames.length);

        const DPR = window.devicePixelRatio || 1;
        const w = canvas.clientWidth || 800;
        const h = canvas.getAttribute('height');
        canvas.width = w * DPR;
        canvas.height = h * DPR;
        ctx.scale(DPR, DPR);

        const PAD = 32;
        const innerW = w - PAD * 2;
        const innerH = h - PAD * 2;
        const maxV = Math.max(1, ...values);

        ctx.clearRect(0, 0, w, h);
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, w, h);

        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(PAD, PAD);
        ctx.lineTo(PAD, h - PAD);
        ctx.lineTo(w - PAD, h - PAD);
        ctx.stroke();

        ctx.fillStyle = '#6b7280';
        ctx.font = '12px Arial';
        const steps = 4;
        for (let i = 1; i <= steps; i++) {
            const y = PAD + innerH * (1 - i / steps);
            ctx.strokeStyle = '#f1f5f9';
            ctx.beginPath();
            ctx.moveTo(PAD, y);
            ctx.lineTo(w - PAD, y);
            ctx.stroke();
            const val = Math.round(maxV * i / steps);
            ctx.fillText(String(val), 6, y + 4);
        }

        const gap = 24;
        const barW = (innerW - gap * (levelNames.length - 1)) / levelNames.length;
        
        values.forEach((v, i) => {
            const x = PAD + i * (barW + gap);
            const barH = innerH * (v / maxV);
            const y = PAD + innerH - barH;
            const grad = ctx.createLinearGradient(0, y, 0, y + barH);
            grad.addColorStop(0, colors[i]);
            grad.addColorStop(1, '#cbd5e1');
            ctx.fillStyle = grad;
            ctx.fillRect(x, y, barW, barH);

            ctx.fillStyle = '#111827';
            ctx.textAlign = 'center';
            ctx.fillText(String(v), x + barW / 2, y - 6);
            ctx.fillStyle = '#6b7280';
            ctx.fillText(levelNames[i], x + barW / 2, h - PAD + 16);
        });
    }

    generateColors(count) {
        const baseColors = ['#a60000', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ef4444', '#06b6d4', '#f97316'];
        const colors = [];
        
        for (let i = 0; i < count; i++) {
            colors.push(baseColors[i % baseColors.length]);
        }
        
        return colors;
    }

    updateLevelsInfo(labels) {
        const levelsInfoEl = document.getElementById('levels-info');
        if (levelsInfoEl && labels.length > 0) {
            levelsInfoEl.textContent = labels.join(' / ');
        }
    }

    updateLegend(counts) {
        const legendEl = document.getElementById('chart-legend');
        if (!legendEl) return;

        const colors = this.generateColors(Object.keys(counts).length);
        const levelNames = Object.keys(counts);
        
        legendEl.innerHTML = levelNames.map((level, index) => {
            const color = colors[index];
            return `<div class="item"><span class="dot" style="background:${color}"></span> ${level}</div>`;
        }).join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});
