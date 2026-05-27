export function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

export function toast(message, type = 'success') {
    const el = document.getElementById('ss-toast');
    if (!el) return;

    el.textContent = message;
    el.className = `fixed bottom-6 right-6 z-[100] max-w-sm rounded-2xl px-5 py-4 text-sm shadow-2xl ${
        type === 'success' ? 'lux-toast-success' : 'lux-toast-error'
    }`;
    el.classList.remove('hidden');

    clearTimeout(el._timer);
    el._timer = setTimeout(() => el.classList.add('hidden'), 3500);
}

export function statusBadge(status) {
    const styles = {
        pending: 'lux-badge lux-badge-pending',
        accepted: 'lux-badge lux-badge-accepted',
        rejected: 'lux-badge lux-badge-rejected',
        completed: 'lux-badge lux-badge-completed',
        perfect: 'lux-badge lux-badge-gold',
        partial: 'lux-badge lux-badge-pending',
    };

    return `<span class="${styles[status] || 'lux-badge lux-badge-gold'}">${status}</span>`;
}

export function emptyState(title, description) {
    return `
        <div class="lux-card border-dashed px-6 py-12 text-center" style="border-style: dashed">
            <p class="lux-heading-md text-lg">${escapeHtml(title)}</p>
            <p class="mt-2 text-sm lux-text-muted">${escapeHtml(description)}</p>
        </div>
    `;
}

export function skillOptions(skills, selected = '') {
    return skills.map((skill) => `
        <option value="${skill.id}" ${String(skill.id) === String(selected) ? 'selected' : ''}>
            ${escapeHtml(skill.name)} (${escapeHtml(skill.category)})
        </option>
    `).join('');
}
