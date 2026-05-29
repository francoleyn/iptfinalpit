import { ApiClient } from './api';
import { emptyState, escapeHtml, skillOptions, statusBadge, toast } from './ui';

export class SkillSwapApp {
    constructor(root) {
        this.root = root;
        this.api = new ApiClient();
        this.user = null;
        this.skills = [];
        this.offers = [];
        this.wants = [];
        this.swaps = [];
        this.perfectMatches = [];
        this.partialMatches = [];
        this.view = 'overview';
    }

    async init() {
        this.renderShell();
        this.bindShellEvents();

        // Always preload skills so select dropdowns can render options
        // even before authentication (skills endpoint is public).
        try {
            await this.loadSkills();
        } catch (e) {
            // ignore load errors; skills will be reloaded after auth
        }

        if (this.api.token) {
            this.bootstrapSession().catch(() => this.showAuth(this.authModeFromPath()));
        } else {
            this.showAuth(this.authModeFromPath());
        }
    }

    authModeFromPath() {
        return window.location.pathname === '/register' ? 'register' : 'login';
    }

    async bootstrapSession() {
        this.user = await this.api.me();
        await this.loadSkills();
        await this.refreshData();
        window.history.replaceState({}, '', '/dashboard');
        this.showDashboard('overview');
    }

    async loadSkills() {
        this.skills = await this.api.skills();
    }

    async refreshData() {
        [this.offers, this.wants, this.perfectMatches, this.partialMatches, this.swaps] = await Promise.all([
            this.api.offers(),
            this.api.wants(),
            this.api.perfectMatches(),
            this.api.partialMatches(),
            this.api.swaps(),
        ]);
    }

    renderShell() {
        this.root.innerHTML = `
            <div class="lux-page">
            <div id="ss-toast" class="hidden"></div>
            <div id="ss-auth"></div>
            <div id="ss-dashboard" class="hidden min-h-screen">
                <main class="px-4 py-8 sm:px-10 lg:py-10">
                    <div id="ss-page-header" class="mb-8"></div>
                    <div id="ss-content"></div>
                </main>
            </div>
            </div>
        `;
    }

    bindShellEvents() {
    }

    showAuth(mode = 'login') {
        this.root.querySelector('#ss-dashboard').classList.add('hidden');
        const auth = this.root.querySelector('#ss-auth');
        auth.classList.remove('hidden');
        auth.innerHTML = mode === 'register' ? this.renderRegister() : this.renderLogin();
        this.bindAuthEvents(mode);
    }

    showDashboard(view = 'overview') {
        this.view = view;
        this.root.querySelector('#ss-auth').classList.add('hidden');
        this.root.querySelector('#ss-dashboard').classList.remove('hidden');
        this.root.querySelector('#ss-user-label').textContent = `${this.user.name} · ${this.user.email}`;
        this.renderNav();
        this.renderPage();
    }

    renderNav() {
        const items = [
            ['overview', 'Overview'],
            ['skills', 'My Skills'],
            ['matches', 'Find Matches'],
            ['swaps', 'Swap Requests'],
            ['profile', 'Profile'],
        ];

        this.root.querySelector('#ss-nav').innerHTML = items.map(([key, label]) => `
            <button data-view="${key}" class="ss-nav-btn lux-nav-btn shrink-0 ${
                this.view === key ? 'lux-nav-btn-active' : ''
            }">${label}</button>
        `).join('');

        this.root.querySelectorAll('.ss-nav-btn').forEach((btn) => {
            btn.addEventListener('click', () => this.showDashboard(btn.dataset.view));
        });
    }

    renderPageHeader(title, subtitle) {
        this.root.querySelector('#ss-page-header').innerHTML = `
            <p class="lux-label">Dashboard</p>
            <h1 class="lux-heading-lg mt-2">${escapeHtml(title)}</h1>
            <p class="mt-2 text-sm lux-text-muted">${escapeHtml(subtitle)}</p>
        `;
    }

    async renderPage() {
        const content = this.root.querySelector('#ss-content');

        if (this.view === 'overview') {
            this.renderPageHeader('Overview', 'Your skill swap activity at a glance.');
            content.innerHTML = this.renderOverview();
            this.bindOverviewEvents();
        } else if (this.view === 'skills') {
            // ensure we have the latest skills (useful after seeding or changes)
            try {
                await this.loadSkills();
            } catch (_) {}

            this.renderPageHeader('My Skills', 'Manage what you teach and what you want to learn.');
            content.innerHTML = this.renderSkillsPage();
            this.bindSkillsEvents();
        } else if (this.view === 'matches') {
            this.renderPageHeader('Find Matches', 'Discover people to trade skills with.');
            content.innerHTML = this.renderMatchesPage();
            this.bindMatchesEvents();
        } else if (this.view === 'swaps') {
            this.renderPageHeader('Swap Requests', 'Track and manage your skill trades.');
            content.innerHTML = this.renderSwapsPage();
            this.bindSwapsEvents();
        } else if (this.view === 'profile') {
            this.renderPageHeader('Profile', 'Your public skill swap profile.');
            content.innerHTML = this.renderProfilePage();
        }
    }

    renderLogin() {
        return `
            <div class="flex min-h-screen items-center justify-center px-4 py-12">
                <div class="lux-card lux-card-glow w-full max-w-md p-10 lux-animate-in">
                    <a href="/" class="text-xs font-semibold text-blue-400 uppercase tracking-wider hover:text-blue-300">← Back home</a>
                    <h1 class="lux-heading-lg mt-6 text-2xl">Welcome back</h1>
                    <p class="mt-3 text-sm lux-text-muted">Sign in to find matches and manage your skill trades.</p>
                    <form id="ss-login-form" class="mt-8 space-y-4">
                        <input type="email" name="email" value="alice@skillswap.test" required placeholder="Email address" class="lux-input">
                        <input type="password" name="password" value="password" required placeholder="Password" class="lux-input">
                        <button type="submit" class="lux-btn-gold w-full">Sign In</button>
                    </form>
                    <p class="mt-6 text-center text-sm lux-text-muted">
                        New member?
                        <button id="ss-go-register" class="text-blue-400 hover:text-blue-300">Sign up free</button>
                    </p>
                    <p id="ss-auth-error" class="mt-3 hidden text-sm text-red-400"></p>
                </div>
            </div>
        `;
    }

    renderRegister() {
        return `
            <div class="flex min-h-screen items-center justify-center px-4 py-12">
                <div class="lux-card lux-card-glow w-full max-w-md p-10 lux-animate-in">
                    <a href="/" class="text-xs font-semibold text-blue-400 uppercase tracking-wider hover:text-blue-300">← Back home</a>
                    <h1 class="lux-heading-lg mt-6 text-2xl">Create your account</h1>
                    <p class="mt-3 text-sm lux-text-muted">Join SkillSwap and start trading skills today.</p>
                    <form id="ss-register-form" class="mt-8 space-y-4">
                        <input type="text" name="name" required placeholder="Full name" class="lux-input">
                        <input type="email" name="email" required placeholder="Email address" class="lux-input">
                        <input type="password" name="password" required placeholder="Password" class="lux-input">
                        <input type="password" name="password_confirmation" required placeholder="Confirm password" class="lux-input">
                        <input type="text" name="location" placeholder="City (optional)" class="lux-input">
                        <textarea name="bio" rows="3" placeholder="A brief introduction" class="lux-textarea"></textarea>
                        <button type="submit" class="lux-btn-gold w-full">Create account</button>
                    </form>
                    <p class="mt-6 text-center text-sm lux-text-muted">
                        Already a member?
                        <button id="ss-go-login" class="text-blue-400 hover:text-blue-300">Sign in</button>
                    </p>
                    <p id="ss-auth-error" class="mt-3 hidden text-sm text-red-400"></p>
                </div>
            </div>
        `;
    }

    bindAuthEvents(mode) {
        const errorEl = this.root.querySelector('#ss-auth-error');

        if (mode === 'login') {
            this.root.querySelector('#ss-go-register').addEventListener('click', () => this.showAuth('register'));
            this.root.querySelector('#ss-login-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                errorEl.classList.add('hidden');
                const form = new FormData(e.target);
                try {
                    const data = await this.api.login({
                        email: form.get('email'),
                        password: form.get('password'),
                    });
                    this.api.setToken(data.token);
                    this.user = data.user;
                    await this.loadSkills();
                    await this.refreshData();
                    toast('Signed in successfully.');
                    window.history.replaceState({}, '', '/dashboard');
                    this.showDashboard('overview');
                } catch (error) {
                    errorEl.textContent = error.message;
                    errorEl.classList.remove('hidden');
                }
            });
        } else {
            this.root.querySelector('#ss-go-login').addEventListener('click', () => this.showAuth('login'));
            this.root.querySelector('#ss-register-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                errorEl.classList.add('hidden');
                const form = new FormData(e.target);
                try {
                    const data = await this.api.register({
                        name: form.get('name'),
                        email: form.get('email'),
                        password: form.get('password'),
                        password_confirmation: form.get('password_confirmation'),
                        location: form.get('location') || null,
                        bio: form.get('bio') || null,
                    });
                    this.api.setToken(data.token);
                    this.user = data.user;
                    await this.loadSkills();
                    await this.refreshData();
                    toast('Account created!');
                    window.history.replaceState({}, '', '/dashboard');
                    this.showDashboard('skills');
                } catch (error) {
                    errorEl.textContent = error.message;
                    errorEl.classList.remove('hidden');
                }
            });
        }
    }

    renderOverview() {
        return `
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                ${this.statCard('Offers', this.offers.length, 'Skills you teach')}
                ${this.statCard('Wants', this.wants.length, 'Skills you seek')}
                ${this.statCard('Matches', this.perfectMatches.length, 'Perfect pairings')}
                ${this.statCard('Swaps', this.swaps.length, 'Active exchanges')}
            </div>
            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <section class="lux-card p-6">
                    <p class="lux-label">Guidance</p>
                    <h2 class="lux-heading-md mt-2 text-xl">Your path</h2>
                    <ol class="mt-5 space-y-4 text-sm lux-text-muted">
                        <li>1. Add offers & wants in <button data-view="skills" class="ss-quick-nav text-blue-400 hover:text-blue-300">My Skills</button></li>
                        <li>2. Find partners in <button data-view="matches" class="ss-quick-nav text-blue-400 hover:text-blue-300">Find Matches</button></li>
                        <li>3. Manage trades in <button data-view="swaps" class="ss-quick-nav text-blue-400 hover:text-blue-300">Swap Requests</button></li>
                    </ol>
                </section>
                <section class="lux-card lux-card-glow p-6">
                    <p class="lux-label">Featured pairing</p>
                    <h2 class="lux-heading-md mt-2 text-xl lux-text-gold">Top match</h2>
                    ${this.perfectMatches.length
                        ? this.matchPreview(this.perfectMatches[0])
                        : '<p class="mt-4 text-sm lux-text-muted">Add skills to reveal your first pairing.</p>'}
                </section>
            </div>
        `;
    }

    bindOverviewEvents() {
        this.root.querySelectorAll('.ss-quick-nav').forEach((btn) => {
            btn.addEventListener('click', () => this.showDashboard(btn.dataset.view));
        });
    }

    statCard(label, value, hint) {
        return `
            <div class="lux-stat">
                <p class="lux-label text-[0.6rem]">${label}</p>
                <p class="lux-stat-value mt-3">${value}</p>
                <p class="mt-2 text-xs lux-text-muted">${hint}</p>
            </div>
        `;
    }

    matchPreview(match) {
        const swap = match.suggested_swap;
        return `
            <div class="mt-4 lux-card-inner p-4">
                <p class="font-medium text-white">${escapeHtml(match.name)}</p>
                <p class="mt-1 text-xs lux-text-muted">${escapeHtml(match.location || 'No location')}</p>
                ${swap ? `<p class="mt-3 text-sm lux-text-gold">${escapeHtml(swap.offered_skill_name)} ↔ ${escapeHtml(swap.requested_skill_name)}</p>` : ''}
            </div>
        `;
    }

    renderSkillsPage() {
        return `
            <div class="grid gap-6 xl:grid-cols-2">
                <section class="lux-card p-6">
                    <p class="lux-label">Teaching</p>
                    <h2 class="lux-heading-md mt-2 text-xl">Skills I offer</h2>
                    <form id="ss-add-offer" class="mt-5 grid gap-3">
                        <select name="skill_id" required class="lux-select">
                            <option value="">Select a skill</option>
                            ${skillOptions(this.skills)}
                        </select>
                        <select name="proficiency_level" class="lux-select">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                        <textarea name="description" rows="2" placeholder="Describe your expertise" class="lux-textarea"></textarea>
                        <button type="submit" class="lux-btn-gold lux-btn-sm w-fit">Add offer</button>
                    </form>
                    <div id="ss-offers-list" class="mt-6 space-y-2">${this.renderOffersList()}</div>
                </section>

                <section class="lux-card p-6">
                    <p class="lux-label">Learning</p>
                    <h2 class="lux-heading-md mt-2 text-xl">Skills I seek</h2>
                    <form id="ss-add-want" class="mt-5 grid gap-3">
                        <select name="skill_id" required class="lux-select">
                            <option value="">Select a skill</option>
                            ${skillOptions(this.skills)}
                        </select>
                        <select name="priority" class="lux-select">
                            <option value="low">Low priority</option>
                            <option value="medium" selected>Medium priority</option>
                            <option value="high">High priority</option>
                        </select>
                        <button type="submit" class="lux-btn-gold lux-btn-sm w-fit">Add want</button>
                    </form>
                    <div id="ss-wants-list" class="mt-6 space-y-2">${this.renderWantsList()}</div>
                </section>
            </div>
        `;
    }

    renderOffersList() {
        if (!this.offers.length) {
            return emptyState('No offers yet', 'Add a skill you can teach to start matching.');
        }

        return this.offers.map((offer) => `
            <div class="lux-card-inner flex items-start justify-between gap-3 px-4 py-3">
                <div>
                    <p class="font-medium text-white">${escapeHtml(offer.skill.name)}</p>
                    <p class="text-xs lux-text-muted">${escapeHtml(offer.proficiency_level)}${offer.description ? ` · ${escapeHtml(offer.description)}` : ''}</p>
                </div>
                <button data-delete-offer="${offer.id}" class="lux-btn-danger">Remove</button>
            </div>
        `).join('');
    }

    renderWantsList() {
        if (!this.wants.length) {
            return emptyState('No wants yet', 'Add a skill you want to learn.');
        }

        return this.wants.map((want) => `
            <div class="lux-card-inner flex items-start justify-between gap-3 px-4 py-3">
                <div>
                    <p class="font-medium text-white">${escapeHtml(want.skill.name)}</p>
                    <p class="text-xs lux-text-muted">${escapeHtml(want.priority)} priority</p>
                </div>
                <button data-delete-want="${want.id}" class="lux-btn-danger">Remove</button>
            </div>
        `).join('');
    }

    bindSkillsEvents() {
        this.root.querySelector('#ss-add-offer').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                await this.api.createOffer({
                    skill_id: Number(form.get('skill_id')),
                    proficiency_level: form.get('proficiency_level'),
                    description: form.get('description') || null,
                });
                await this.refreshData();
                toast('Offer added.');
                this.renderPage();
            } catch (error) {
                toast(error.message, 'error');
            }
        });

        this.root.querySelector('#ss-add-want').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                await this.api.createWant({
                    skill_id: Number(form.get('skill_id')),
                    priority: form.get('priority'),
                });
                await this.refreshData();
                toast('Want added.');
                this.renderPage();
            } catch (error) {
                toast(error.message, 'error');
            }
        });

        this.root.querySelectorAll('[data-delete-offer]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                try {
                    await this.api.deleteOffer(btn.dataset.deleteOffer);
                    await this.refreshData();
                    toast('Offer removed.');
                    this.renderPage();
                } catch (error) {
                    toast(error.message, 'error');
                }
            });
        });

        this.root.querySelectorAll('[data-delete-want]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                try {
                    await this.api.deleteWant(btn.dataset.deleteWant);
                    await this.refreshData();
                    toast('Want removed.');
                    this.renderPage();
                } catch (error) {
                    toast(error.message, 'error');
                }
            });
        });
    }

    renderMatchesPage() {
        return `
            <div class="mb-6 flex flex-wrap gap-3">
                <button id="ss-refresh-perfect" class="lux-btn-gold lux-btn-sm">Refresh perfect matches</button>
                <button id="ss-refresh-partial" class="lux-btn-ghost lux-btn-sm">Refresh suggestions</button>
            </div>
            <div class="grid gap-8 xl:grid-cols-2">
                <section>
                    <p class="lux-label mb-4">Harmony</p>
                    <div id="ss-perfect-list" class="space-y-4">${this.renderMatchCards(this.perfectMatches, true)}</div>
                </section>
                <section>
                    <p class="lux-label mb-4">Suggestions</p>
                    <div id="ss-partial-list" class="space-y-4">${this.renderMatchCards(this.partialMatches, false)}</div>
                </section>
            </div>
        `;
    }

    renderMatchCards(matches, showTrade) {
        if (!matches.length) {
            return emptyState('No matches found', showTrade
                ? 'Make sure you have both offers and wants listed.'
                : 'Try adding more skills to discover suggestions.');
        }

        return matches.map((match) => {
            const swap = match.suggested_swap;
            return `
                <article class="lux-card p-5 ${showTrade ? 'lux-match-perfect lux-card-glow' : 'lux-match-partial'}">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="lux-heading-md text-lg">${escapeHtml(match.name)}</h3>
                            <p class="mt-1 text-xs lux-text-muted">${escapeHtml(match.location || 'No location')} · Rating: ${match.average_rating ?? 'N/A'}</p>
                        </div>
                        ${statusBadge(match.match_type)}
                    </div>
                    ${match.bio ? `<p class="mt-3 text-sm lux-text-muted">${escapeHtml(match.bio)}</p>` : ''}
                    <p class="mt-3 text-xs lux-text-gold">Offers: ${match.offers.map((o) => escapeHtml(o.skill.name)).join(', ')}</p>
                    <p class="text-xs lux-text-muted">Seeks: ${match.wants.map((w) => escapeHtml(w.skill.name)).join(', ')}</p>
                    ${showTrade && swap ? `
                        <div class="mt-5 lux-card-inner p-4">
                            <p class="lux-label text-[0.6rem]">Proposed exchange</p>
                            <p class="mt-2 text-sm text-white">${escapeHtml(swap.offered_skill_name)} ↔ ${escapeHtml(swap.requested_skill_name)}</p>
                            <button
                                class="ss-request-trade lux-btn-gold mt-4 w-full lux-btn-sm"
                                data-receiver-id="${match.id}"
                                data-offered-skill-id="${swap.offered_skill_id}"
                                data-requested-skill-id="${swap.requested_skill_id}"
                                data-match-name="${escapeHtml(match.name)}"
                                data-offered-name="${escapeHtml(swap.offered_skill_name)}"
                                data-requested-name="${escapeHtml(swap.requested_skill_name)}"
                            >Request exchange</button>
                        </div>
                    ` : ''}
                </article>
            `;
        }).join('');
    }

    bindMatchesEvents() {
        this.root.querySelector('#ss-refresh-perfect').addEventListener('click', async () => {
            this.perfectMatches = await this.api.perfectMatches();
            this.root.querySelector('#ss-perfect-list').innerHTML = this.renderMatchCards(this.perfectMatches, true);
            this.bindTradeButtons();
            toast('Perfect matches updated.');
        });

        this.root.querySelector('#ss-refresh-partial').addEventListener('click', async () => {
            this.partialMatches = await this.api.partialMatches();
            this.root.querySelector('#ss-partial-list').innerHTML = this.renderMatchCards(this.partialMatches, false);
            toast('Partial matches updated.');
        });

        this.bindTradeButtons();
    }

    bindTradeButtons() {
        this.root.querySelectorAll('.ss-request-trade').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const message = prompt(
                    `Send trade request to ${btn.dataset.matchName}?\n\nYou offer: ${btn.dataset.offeredName}\nYou learn: ${btn.dataset.requestedName}`,
                    `Hi! I'd like to swap ${btn.dataset.offeredName} for ${btn.dataset.requestedName}.`
                );
                if (message === null) return;

                btn.disabled = true;
                try {
                    await this.api.createSwap({
                        receiver_id: Number(btn.dataset.receiverId),
                        offered_skill_id: Number(btn.dataset.offeredSkillId),
                        requested_skill_id: Number(btn.dataset.requestedSkillId),
                        message: message || null,
                    });
                    this.swaps = await this.api.swaps();
                    toast(`Trade request sent to ${btn.dataset.matchName}!`);
                    btn.textContent = 'Request Sent';
                } catch (error) {
                    toast(error.message, 'error');
                    btn.disabled = false;
                }
            });
        });
    }

    renderSwapsPage() {
        if (!this.swaps.length) {
            return emptyState('No swap requests yet', 'Find a perfect match and click Request Trade.');
        }

        return `<div class="space-y-4">${this.swaps.map((swap) => this.renderSwapCard(swap)).join('')}</div>`;
    }

    renderSwapCard(swap) {
        const isRequester = swap.requester_id === this.user.id;
        const isReceiver = swap.receiver_id === this.user.id;
        const other = isRequester ? swap.receiver : swap.requester;

        let actions = '';
        if (swap.status === 'pending' && isReceiver) {
            actions = `
                <button data-swap-action="accept" data-swap-id="${swap.id}" class="lux-btn-gold lux-btn-sm">Accept</button>
                <button data-swap-action="reject" data-swap-id="${swap.id}" class="lux-btn-danger px-3 py-1.5">Decline</button>
            `;
        }
        if (swap.status === 'pending' && isRequester) {
            actions += `<button data-swap-action="cancel" data-swap-id="${swap.id}" class="lux-btn-ghost lux-btn-sm">Withdraw</button>`;
        }
        if (swap.status === 'accepted') {
            actions += `<button data-swap-action="complete" data-swap-id="${swap.id}" class="lux-btn-gold lux-btn-sm">Mark complete</button>`;
        }
        if (swap.status === 'completed') {
            actions += `
                <button
                    data-swap-action="review"
                    data-swap-id="${swap.id}"
                    data-reviewee-id="${other.id}"
                    data-reviewee-name="${escapeHtml(other.name)}"
                    class="lux-btn-ghost lux-btn-sm"
                >Leave review</button>
            `;
        }

        return `
            <article class="lux-card p-6">
                <div class="flex flex-wrap items-center gap-3">
                    <h3 class="lux-heading-md text-lg">${escapeHtml(swap.requester.name)} → ${escapeHtml(swap.receiver.name)}</h3>
                    ${statusBadge(swap.status)}
                </div>
                <p class="mt-3 text-sm lux-text-gold">${escapeHtml(swap.offered_skill.name)} ↔ ${escapeHtml(swap.requested_skill.name)}</p>
                ${swap.message ? `<p class="mt-3 text-sm italic lux-text-muted">"${escapeHtml(swap.message)}"</p>` : ''}
                <div class="mt-5 flex flex-wrap gap-2">${actions}</div>
            </article>
        `;
    }

    bindSwapsEvents() {
        this.root.querySelectorAll('[data-swap-action]').forEach((btn) => {
            btn.addEventListener('click', () => this.handleSwapAction(btn));
        });
    }

    async handleSwapAction(btn) {
        const { swapAction, swapId, revieweeId, revieweeName } = btn.dataset;

        try {
            if (swapAction === 'accept') {
                await this.api.acceptSwap(swapId);
                toast('Swap accepted!');
            } else if (swapAction === 'reject') {
                await this.api.rejectSwap(swapId);
                toast('Swap rejected.');
            } else if (swapAction === 'complete') {
                await this.api.completeSwap(swapId);
                toast('Swap marked complete!');
            } else if (swapAction === 'cancel') {
                if (!confirm('Cancel this swap request?')) return;
                await this.api.cancelSwap(swapId);
                toast('Swap cancelled.');
            } else if (swapAction === 'review') {
                const rating = prompt(`Rate ${revieweeName} from 1 to 5:`);
                if (!rating || rating < 1 || rating > 5) return;
                const comment = prompt('Optional comment:') || null;
                await this.api.createReview({
                    swap_request_id: Number(swapId),
                    reviewee_id: Number(revieweeId),
                    rating: Number(rating),
                    comment,
                });
                toast('Review submitted!');
            }

            this.swaps = await this.api.swaps();
            this.renderPage();
        } catch (error) {
            toast(error.message, 'error');
        }
    }

    renderProfilePage() {
        return `
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="lux-card lux-card-glow p-8">
                    <p class="lux-label">Member profile</p>
                    <h2 class="lux-heading-lg mt-3">${escapeHtml(this.user.name)}</h2>
                    <p class="mt-2 text-sm lux-text-muted">${escapeHtml(this.user.email)}</p>
                    <p class="mt-1 text-sm lux-text-gold">${escapeHtml(this.user.location || 'Location not set')}</p>
                    <div class="lux-divider my-6"></div>
                    <p class="text-sm leading-relaxed lux-text-muted">${escapeHtml(this.user.bio || 'No biography provided.')}</p>
                </section>
                <section class="lux-card p-6">
                    <p class="lux-label">Portfolio</p>
                    <h3 class="lux-heading-md mt-2 text-xl">Skills summary</h3>
                    <div class="mt-6 space-y-5 text-sm">
                        <div>
                            <p class="lux-label text-[0.6rem]">Teaching</p>
                            <p class="mt-2 lux-text-gold">${this.offers.map((o) => escapeHtml(o.skill.name)).join(', ') || 'None listed'}</p>
                        </div>
                        <div>
                            <p class="lux-label text-[0.6rem]">Learning</p>
                            <p class="mt-2 lux-text-muted">${this.wants.map((w) => escapeHtml(w.skill.name)).join(', ') || 'None listed'}</p>
                        </div>
                    </div>
                </section>
            </div>
        `;
    }

    async handleLogout() {
        try {
            await this.api.logout();
        } catch (_) {
            // ignore
        }
        this.api.setToken(null);
        this.user = null;
        toast('Signed out.');
        window.history.replaceState({}, '', '/login');
        this.showAuth('login');
    }
}
