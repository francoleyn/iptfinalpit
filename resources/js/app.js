import './bootstrap';

import { SkillSwapApp } from './skillswap/app';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('skillswap-root');
    if (root) {
        new SkillSwapApp(root).init();
    }
});
