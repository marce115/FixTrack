import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.createIcons = createIcons;
window.lucideIcons = icons;
window.Chart = Chart;

window.renderIcons = () => {
    createIcons({ icons });
};

document.addEventListener('DOMContentLoaded', () => {
    window.renderIcons();
});

Alpine.start();
