const API = '/api';

export class ApiClient {
    constructor() {
        this.token = localStorage.getItem('skillswap_token');
    }

    setToken(token) {
        this.token = token;
        if (token) {
            localStorage.setItem('skillswap_token', token);
        } else {
            localStorage.removeItem('skillswap_token');
        }
    }

    async request(path, options = {}) {
        const headers = {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(this.token ? { Authorization: `Bearer ${this.token}` } : {}),
            ...options.headers,
        };

        const response = await fetch(`${API}${path}`, { ...options, headers });
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const message = data.message
                || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Request failed');
            throw new Error(message);
        }

        return data;
    }

    register(payload) {
        return this.request('/register', { method: 'POST', body: JSON.stringify(payload) });
    }

    login(payload) {
        return this.request('/login', { method: 'POST', body: JSON.stringify(payload) });
    }

    logout() {
        return this.request('/logout', { method: 'POST' });
    }

    me() {
        return this.request('/me');
    }

    skills() {
        return this.request('/skills');
    }

    offers() {
        return this.request('/offers');
    }

    createOffer(payload) {
        return this.request('/offers', { method: 'POST', body: JSON.stringify(payload) });
    }

    deleteOffer(id) {
        return this.request(`/offers/${id}`, { method: 'DELETE' });
    }

    wants() {
        return this.request('/wants');
    }

    createWant(payload) {
        return this.request('/wants', { method: 'POST', body: JSON.stringify(payload) });
    }

    deleteWant(id) {
        return this.request(`/wants/${id}`, { method: 'DELETE' });
    }

    perfectMatches() {
        return this.request('/matches');
    }

    partialMatches() {
        return this.request('/matches/partial');
    }

    swaps() {
        return this.request('/swaps');
    }

    createSwap(payload) {
        return this.request('/swaps', { method: 'POST', body: JSON.stringify(payload) });
    }

    acceptSwap(id) {
        return this.request(`/swaps/${id}/accept`, { method: 'PATCH' });
    }

    rejectSwap(id) {
        return this.request(`/swaps/${id}/reject`, { method: 'PATCH' });
    }

    completeSwap(id) {
        return this.request(`/swaps/${id}/complete`, { method: 'PATCH' });
    }

    cancelSwap(id) {
        return this.request(`/swaps/${id}`, { method: 'DELETE' });
    }

    createReview(payload) {
        return this.request('/reviews', { method: 'POST', body: JSON.stringify(payload) });
    }

    userProfile(id) {
        return this.request(`/users/${id}/profile`);
    }

    userReviews(id) {
        return this.request(`/users/${id}/reviews`);
    }
}
