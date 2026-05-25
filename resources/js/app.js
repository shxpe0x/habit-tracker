// Chart.js загружается только когда реально нужен (страница статистики).
window.loadChart = async () => {
    if (window.Chart) return window.Chart;
    const { default: Chart } = await import('chart.js/auto');
    window.Chart = Chart;
    return Chart;
};

// Делегирование mousemove для всех .spotlight-card на странице.
// Один листенер на document вместо N листенеров на каждой карточке —
// быстрее и не пересоздаётся при Livewire-обновлениях.
// На touch-устройствах не подключаем — экономим CPU.
if (window.matchMedia('(hover: hover)').matches) {
    document.addEventListener('mousemove', (e) => {
        const card = e.target.closest('.spotlight-card');
        if (!card) return;
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--spotlight-x', `${e.clientX - rect.left}px`);
        card.style.setProperty('--spotlight-y', `${e.clientY - rect.top}px`);
    }, { passive: true });
}


// Эффект печатной машинки (TextType из reactbits) — на чистом Alpine
document.addEventListener('alpine:init', () => {
    window.Alpine.data('textType', (config) => ({
        texts: config.texts || [''],
        typingSpeed: config.typingSpeed || 80,
        deletingSpeed: config.deletingSpeed || 40,
        pauseDuration: config.pauseDuration || 1800,
        loop: config.loop ?? true,

        index: 0,
        display: '',
        deleting: false,

        init() {
            this.tick();
        },

        tick() {
            const full = this.texts[this.index];

            if (!this.deleting && this.display.length < full.length) {
                this.display = full.slice(0, this.display.length + 1);
                setTimeout(() => this.tick(), this.typingSpeed);
                return;
            }

            if (!this.deleting && this.display.length === full.length) {
                if (this.texts.length === 1 && !this.loop) return;
                setTimeout(() => { this.deleting = true; this.tick(); }, this.pauseDuration);
                return;
            }

            if (this.deleting && this.display.length > 0) {
                this.display = this.display.slice(0, -1);
                setTimeout(() => this.tick(), this.deletingSpeed);
                return;
            }

            if (this.deleting && this.display.length === 0) {
                this.deleting = false;
                this.index = (this.index + 1) % this.texts.length;
                setTimeout(() => this.tick(), 250);
            }
        },
    }));
});
