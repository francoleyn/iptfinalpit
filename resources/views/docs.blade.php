@extends('layouts.app')

@section('title', 'API Reference — Skill Swap')

@section('content')
<div class="lux-page">
    <div class="mx-auto max-w-4xl px-6 py-12">
        <a href="/" class="tp-link text-blue-400 hover:text-blue-300">← Back home</a>
        <p class="lux-label mt-6">Developer</p>
        <h1 class="lux-heading-lg mt-2">API Reference</h1>
        <p class="mt-3 lux-text-muted">Base URL: <code class="text-blue-400">{{ url('/api') }}</code></p>

        <div class="mt-10 space-y-6">
            @foreach ([
                'Auth' => [
                    'POST /register' => 'Create account, returns token',
                    'POST /login' => 'Login, returns token',
                    'POST /logout' => 'Revoke token (auth required)',
                    'GET /me' => 'Current user (auth required)',
                ],
                'Skills' => [
                    'GET /skills' => 'List all skills',
                    'GET /skills/{id}' => 'Show one skill',
                    'POST /skills' => 'Create skill (admin only)',
                ],
                'Offers & Wants' => [
                    'GET|POST /offers' => 'List or create your teaching offers',
                    'GET|POST /wants' => 'List or create skills you want to learn',
                ],
                'Matching' => [
                    'GET /matches' => 'Perfect mutual matches',
                    'GET /matches/partial' => 'One-direction suggestions',
                    'GET /users/{id}/profile' => 'Public profile with ratings',
                ],
                'Swaps & Reviews' => [
                    'GET|POST /swaps' => 'List or send swap requests',
                    'PATCH /swaps/{id}/accept|reject|complete' => 'Manage swap lifecycle',
                    'POST /reviews' => 'Rate partner after completed swap',
                ],
            ] as $section => $endpoints)
                <section class="lux-card p-6">
                    <h2 class="lux-heading-md text-xl">{{ $section }}</h2>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($endpoints as $route => $desc)
                            <li class="flex flex-col gap-1 border-b border-white/5 pb-3 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                                <code class="text-blue-400">{{ $route }}</code>
                                <span class="lux-text-muted">{{ $desc }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </div>
</div>
@endsection
