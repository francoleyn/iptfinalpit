@extends('layouts.app')

@section('title', 'Skill Swap — Smart Skill Trading')

@section('content')
<div class="lux-page">
    <div class="mx-auto max-w-6xl px-6 pb-16">
        {{-- Navigation --}}
        <nav class="tp-nav lux-animate-in">
            <a href="/" class="lux-logo">Skill<span>Swap</span></a>
            <div class="tp-nav-links">
                <a href="/">Home</a>
                <a href="#features">Features</a>
                <a href="#process">How It Works</a>
                <a href="/docs">API</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="/login" class="tp-link hidden sm:inline">Login</a>
                <a href="/register" class="lux-btn-gold lux-btn-sm">Sign Up</a>
            </div>
        </nav>

        {{-- Hero --}}
        <section class="lux-animate-in lux-animate-delay-1 mt-12 text-center lg:mt-20 lg:text-left">
            <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                <div>
                    <h1 class="lux-heading-xl">
                        Empower Your Future with<br>
                        <span class="lux-text-gold">Smart Skill Swap</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-xl text-base leading-relaxed lux-text-muted lg:mx-0 lg:text-lg">
                        Trade knowledge instead of money. This platform connects users looking to swap skills instantly. Updated and tested locally by Faisal.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                       <a href="/register" class="lux-btn-gold hover:shadow-lg hover:scale-105 transition duration-300">
    Get Started Now
</a>
                        <a href="#features" class="lux-btn-ghost">Explore Features</a>
                    </div>
                </div>
                <div class="lux-card lux-card-glow p-6 lg:p-8 hover:scale-[1.02] transition duration-300">
                    <p class="lux-label">Live match preview</p>
                    <div class="mt-4 space-y-3">
                        <div class="lux-card-inner p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-white">Alice</span>
                                <span class="lux-badge lux-badge-gold">98% match</span>
                            </div>
                            <p class="mt-2 text-sm lux-text-muted">Teaches Photoshop · Wants Guitar</p>
                        </div>
                        <div class="flex justify-center py-1">
                            <span class="rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-300">↕ Swap</span>
                        </div>
                        <div class="lux-card-inner p-4">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-white">Bob</span>
                                <span class="lux-badge lux-badge-gold">98% match</span>
                            </div>
                            <p class="mt-2 text-sm lux-text-muted">Teaches Guitar · Wants Photoshop</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Partners / Categories --}}
        <section class="lux-animate-in lux-animate-delay-2 mt-24 text-center">
            <p class="text-sm font-medium lux-text-muted">Skill categories on the platform</p>
            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
               @foreach ([
                     ['💻', 'Coding'],
                     ['🌐', 'Languages'],
                     ['🎵', 'Music'],
                     ['🎨', 'Art'],
                     ['⚽', 'Sports'],
                     ['🍳', 'Cooking']
                ] as [$icon, $cat])
                    <div class="tp-partner skill-card flex flex-col items-center justify-center gap-2
                    cursor-pointer transition-all duration-300
                    hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20 hover:border-blue-400/40
                    border border-white/10 rounded-lg p-4"
         onclick="selectSkill(this, '{{ $cat }}')">

        <span class="text-2xl">{{ $icon }}</span>
        <span class="font-medium text-white">{{ $cat }}</span>
    </div>
@endforeach
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="mt-28 lux-animate-in lux-animate-delay-3">
            <div class="text-center">
                <h2 class="lux-heading-lg">Why Traders Love <span class="lux-text-gold">SkillSwap</span></h2>
                <p class="mx-auto mt-4 max-w-2xl lux-text-muted">Everything you need to discover partners, request trades, and build your reputation.</p>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['⚡', 'Real-Time Matching', 'Our algorithm finds perfect and partial matches based on what you offer and want to learn.'],
                    ['🔄', 'Zero Cost Exchanges', 'No fees, no payments — just pure skill-for-skill barter between members.'],
                    ['⭐', 'Trusted Reviews', 'Rate partners after every completed swap and build a verified reputation profile.'],
                ] as [$icon, $title, $desc])
                    <div class="lux-card p-6 transition hover:border-blue-500/30 hover:shadow-lg hover:shadow-blue-500/10">
                        <div class="tp-feature-icon">{{ $icon }}</div>
                        <h3 class="mt-5 text-lg font-semibold text-white">{{ $title }}</h3>
                        <p class="mt-3 text-sm leading-relaxed lux-text-muted">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Process --}}
        <section id="process" class="mt-28">
            <div class="text-center">
                <h2 class="lux-heading-lg">Your First Swap, Made Simple</h2>
            </div>
            <div class="mt-12 grid gap-4 md:grid-cols-3">
                <div class="tp-step">
                    <p class="tp-step-num text-blue-300">01</p>
                    <h3 class="mt-4 font-semibold text-white">Create an Account</h3>
                    <p class="mt-2 text-sm lux-text-muted">Sign up and build your profile with bio and location.</p>
                </div>
                <div class="tp-step tp-step-active">
                    <p class="tp-step-num">02</p>
                    <h3 class="mt-4 font-semibold text-white">Add Skills</h3>
                    <p class="mt-2 text-sm text-blue-100">List what you teach and what you want to learn from 30+ skills.</p>
                </div>
                <div class="tp-step">
                    <p class="tp-step-num text-blue-300">03</p>
                    <h3 class="mt-4 font-semibold text-white">Start Swapping</h3>
                    <p class="mt-2 text-sm lux-text-muted">Find matches, send requests, and complete your first trade.</p>
                </div>
            </div>
        </section>

        {{-- Stats + Testimonial --}}
        <section class="mt-28 grid gap-8 lg:grid-cols-2">
            <div class="lux-card lux-card-glow flex flex-col items-center justify-center p-10 text-center">
                <p class="tp-stat-big">20+</p>
                <p class="mt-3 text-lg font-semibold text-white">Demo members ready to swap</p>
                <p class="mt-2 text-sm lux-text-muted">10 perfect-match pairs seeded for instant testing</p>
            </div>
            <div class="lux-card p-8">
                <p class="lux-label">Member feedback</p>
                <blockquote class="mt-4 text-lg leading-relaxed text-white">
                    "I taught Photoshop and learned guitar in return — the matching was instant. Best platform for skill exchange I've used."
                </blockquote>
                <div class="mt-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/20 text-sm font-bold text-blue-300">A</div>
                    <div>
                        <p class="font-semibold text-white">Alice Chen</p>
                        <p class="text-sm lux-text-muted">Designer · Manila</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Demo CTA --}}
        <section class="mt-16 lux-card lux-card-glow p-8 text-center lg:text-left">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="lux-heading-md text-xl">Try the demo now</h3>
                    <p class="mt-2 text-sm lux-text-muted">Login as <code class="text-blue-400">alice@skillswap.test</code> — password: <code class="text-blue-400">password</code></p>
                </div>
                <a href="/login" class="lux-btn-gold lux-btn-sm shrink-0">Open Dashboard</a>
            </div>
        </section>

        <footer class="mt-20 border-t border-white/5 pt-8 text-center">
            <p class="text-sm lux-text-muted tracking-wide">© {{ date('Y') }} SkillSwap — Built for Smart Skill Exchange</p>
        </footer>
    </div>
</div>
@endsection
