<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">


    {{-- Gentelella CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/main-v4-DDS6x4g-.css') }}">

    {{-- .menu-popover sets display:flex, which beats the browser's [hidden] rule,
         so the account menu shows by default. This makes `hidden` actually hide it. --}}
    <style>
        .account-menu[hidden] { display: none; }
    </style>

    {{ $head ?? '' }}

    @stack('styles')
</head>

<body data-shell="admin" data-page="dashboard" data-breadcrumb="Home > Dashboard">



    @include('partials.admin.sidebar')


    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" type="button" aria-label="Open menu" aria-controls="sidebar"
                aria-expanded="false">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <nav class="breadcrumb" aria-label="Breadcrumb"><span class="current" aria-current="page">Home</span></nav>
        </div>
       
        <div class="topbar-right">
          
            <button class="tb-btn theme-toggle" type="button" title="Toggle theme" aria-label="Toggle theme"
                aria-pressed="false">
                <svg class="theme-icon-light" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="12" cy="12" r="4" />
                    <path
                        d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                </svg>
                <svg class="theme-icon-dark" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
            </button>
            <button class="tb-btn tb-notifications" type="button" title="Notifications" aria-label="Notifications"
                aria-haspopup="dialog" aria-expanded="false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" aria-hidden="true">
                    <path d="M12 3a6 6 0 00-6 6c0 6-3 7-3 7h18s-3-1-3-7a6 6 0 00-6-6z" />
                    <path d="M10.5 21a1.5 1.5 0 003 0" />
                </svg>
                <span class="dot"></span>
            </button>
            <button class="tb-btn tb-messages" type="button" title="Messages" aria-label="Messages"
                aria-haspopup="dialog" aria-expanded="false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" aria-hidden="true">
                    <rect x="2" y="4" width="20" height="16" rx="3" />
                    <path d="M2 7l10 6 10-6" />
                </svg>
            </button>
            {{-- Account menu. The whole popover is plain HTML below — add/remove
                 <a class="menu-item"> lines to change the menu. It's hidden until
                 the avatar is clicked (see the toggle script at the bottom). --}}
            <div class="account-menu-wrap" style="position:relative">
                <button class="tb-avatar" type="button" aria-label="Account menu" aria-haspopup="menu"
                    aria-expanded="false">A</button>

                <div class="menu-popover account-menu" role="menu" hidden
                    style="position:absolute; top:calc(100% + 6px); right:0;">
                    <a class="menu-item" role="menuitem" href="#">Profile</a>
                    <a class="menu-item" role="menuitem" href="#">Account settings</a>
                   
                    <div class="menu-separator"></div>
                    {{-- Real logout: POST + CSRF to admin.logout --}}
                    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
                        @csrf
                        <input type="submit" value="Destroy" class="menu-item"  style="width:100%"/>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main id="main-content" tabindex="-1" class="main">
        <div class="page-wrapper">
            {{ $slot }}
        </div>
     

    </main>


    <script type="module" crossorigin src="{{ asset('js/rolldown-runtime-DEgBLETi.js') }}"></script>

  <script type="module" crossorigin src="{{ asset('js/toast-DgCSlJPv.js') }}"></script> 

     <script type="module" crossorigin src="{{ asset('js/menus-BVcs0GJR.js') }}"></script> 

    <script type="module" crossorigin src="{{ asset('js/modal-MTuCfURV.js') }}"></script>

     <script type="module" crossorigin src="{{ asset('js/main-v4-BFwmMcfm.js') }}"></script> 

 <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    {{-- Show/hide the account menu above. Also blocks the Gentelella bundle's own
         demo avatar menu (toast-only "Sign out") so only our HTML menu appears. --}}
    <script>
        (function () {
            const wrap = document.querySelector('.account-menu-wrap');
            if (!wrap) return;

            const avatar = wrap.querySelector('.tb-avatar');
            const menu = wrap.querySelector('.account-menu');

            const open = () => { menu.hidden = false; avatar.setAttribute('aria-expanded', 'true'); };
            const close = () => { menu.hidden = true; avatar.setAttribute('aria-expanded', 'false'); };

            // Capture phase + stopImmediatePropagation stops the theme bundle's
            // click handler from opening its own demo popover.
            avatar.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopImmediatePropagation();
                menu.hidden ? open() : close();
            }, true);

            // The theme intercepts some button clicks and shows a "✓" toast
            // instead of letting them act. Capture-phase here runs BEFORE that
            // handler: we block it and submit the logout form ourselves.
       
        })();
    </script>

    @stack('scripts')

</body>

</html>
