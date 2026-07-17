@push('styles')
<style>
    :root {
        --bl-bg: #0b1120;
        --bl-accent: #6366f1;
        --bl-accent-2: #8b5cf6;
    }

    .admin-login {
        min-height: 100vh;
        background:
            radial-gradient(1200px 600px at 100% 0%, rgba(139, 92, 246, 0.18), transparent 55%),
            radial-gradient(900px 500px at 0% 100%, rgba(99, 102, 241, 0.20), transparent 55%),
            linear-gradient(135deg, #0b1120 0%, #111827 55%, #0b1120 100%);
    }

    .login-shell {
        width: 100%;
        max-width: 960px;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.6);
    }

    /* Left brand panel */
    .login-brand {
        position: relative;
        background:
            radial-gradient(600px 300px at 20% 10%, rgba(255, 255, 255, 0.12), transparent 60%),
            linear-gradient(160deg, var(--bl-accent) 0%, var(--bl-accent-2) 100%);
        color: #fff;
        padding: 3rem 2.75rem;
        overflow: hidden;
    }

    .login-brand::after {
        content: "";
        position: absolute;
        inset: auto -40% -45% auto;
        width: 340px;
        height: 340px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .brand-logo {
        width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(6px);
        font-weight: 700;
        font-size: 1.35rem;
        letter-spacing: -0.5px;
    }

    .brand-feature {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 0.925rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .brand-feature .tick {
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        font-size: 0.75rem;
    }

    /* Right form panel */
    .login-form-panel {
        background: #ffffff;
        padding: 3rem 2.75rem;
    }

    .login-form-panel .form-control {
        border-radius: 0.75rem;
        padding: 0.7rem 0.9rem;
        border-color: #e2e8f0;
    }

    .login-form-panel .form-control:focus {
        border-color: var(--bl-accent);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
    }

    .input-affix {
        position: relative;
    }

    .input-affix .affix-icon {
        position: absolute;
        top: 50%;
        left: 0.9rem;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .input-affix .form-control {
        padding-left: 2.65rem;
    }

    .btn-login {
        border-radius: 0.75rem;
        padding: 0.7rem 1rem;
        font-weight: 600;
        background: linear-gradient(135deg, var(--bl-accent) 0%, var(--bl-accent-2) 100%);
        border: none;
        box-shadow: 0 10px 24px -8px rgba(99, 102, 241, 0.6);
    }

    .btn-login:hover,
    .btn-login:focus {
        filter: brightness(1.05);
        box-shadow: 0 12px 28px -8px rgba(99, 102, 241, 0.7);
    }

    .btn-toggle-pw {
        border: 1px solid #e2e8f0;
        border-left: none;
        border-radius: 0 0.75rem 0.75rem 0;
        background: #fff;
        color: #64748b;
    }

    .btn-toggle-pw:hover {
        background: #f8fafc;
        color: #334155;
    }

    @media (max-width: 767.98px) {
        .login-brand { display: none !important; }
        .login-form-panel { padding: 2.25rem 1.5rem; }
    }
</style>
@endpush

<div class="admin-login d-flex align-items-center justify-content-center p-3">
    <div class="login-shell">
        <div class="row g-0">
            {{-- Brand panel --}}
            <div class="col-md-6 login-brand d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="brand-logo">BL</span>
                    <div>
                        <div class="fw-bold fs-5 lh-1">BatchLinks</div>
                        <small class="opacity-75">Admin Console</small>
                    </div>
                </div>

                <div class="my-auto py-4">
                    <h2 class="fw-bold mb-3" style="letter-spacing: -0.5px;">Welcome back.</h2>
                    <p class="opacity-75 mb-4" style="max-width: 22rem;">
                        Sign in to manage your links, monitor activity, and keep everything running smoothly.
                    </p>

                    <div class="d-flex flex-column gap-3">
                        <div class="brand-feature"><span class="tick">&#10003;</span> Secure superadmin access</div>
                        <div class="brand-feature"><span class="tick">&#10003;</span> Real-time dashboard insights</div>
                        <div class="brand-feature"><span class="tick">&#10003;</span> Full control over your batches</div>
                    </div>
                </div>

                <small class="opacity-50 mt-auto">&copy; {{ date('Y') }} BatchLinks. All rights reserved.</small>
            </div>

            {{-- Form panel --}}
            <div class="col-md-6 login-form-panel d-flex flex-column justify-content-center">
                <div class="text-center text-md-start mb-4">
                    <h1 class="h3 fw-bold mb-1">Admin Login</h1>
                    <p class="text-muted small mb-0">Enter your credentials to continue</p>
                </div>

                @error('credentials')
                    <div class="alert alert-danger d-flex align-items-center py-2 small rounded-3" role="alert">
                        <span class="me-2">&#9888;</span>
                        <div>{{ $message }}</div>
                    </div>
                @enderror

                <form wire:submit.prevent="login" class="d-flex flex-column gap-3">
                    <div>
                        <label for="email" class="form-label small fw-semibold text-secondary">Email address</label>
                        <div class="input-affix">
                            <span class="affix-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zM10.197 8.244 16 11.801V4.697z"/></svg>
                            </span>
                            <input
                                wire:model.blur="email"
                                id="email"
                                type="email"
                                autocomplete="email"
                                autofocus
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="admin@example.com"
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-affix" x-data="{ show: false }">
                            <span class="affix-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/></svg>
                            </span>
                            <div class="d-flex">
                                <input
                                    wire:model.blur="password"
                                    id="password"
                                    :type="show ? 'text' : 'password'"
                                    type="password"
                                    autocomplete="current-password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    style="border-radius: 0.75rem 0 0 0.75rem; border-right: none;"
                                    placeholder="Enter your password"
                                >
                                <button type="button" class="btn btn-toggle-pw px-3" @click="show = !show" tabindex="-1" aria-label="Toggle password visibility">
                                    <span x-show="!show">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
                                    </span>
                                    <span x-show="show" style="display:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/><path d="m2.354 1.646 12 12-.708.708-12-12z"/></svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input wire:model="remember" type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label small text-secondary" for="remember">Remember me</label>
                        </div>
                    </div>
                       {{-- this removes the div during login() <span wire:loading.remove wire:target="login">Sign In</span> --}}

                     {{-- this adds the div during login    <span wire:loading wire:target="login"> --}}
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="login"
                        class="btn btn-login btn-lg w-100 text-white mt-1"
                    >
                        <span wire:loading.remove wire:target="login">Sign In</span>                    
                        <span wire:loading wire:target="login">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>   
                            Signing in&hellip;
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
