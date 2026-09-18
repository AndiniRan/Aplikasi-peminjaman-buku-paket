<header class="admin-topbar">
    <!-- LEFT -->
    <div class="topbar-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <h1 class="topbar-page-title">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    <!-- PROFILE -->
    <div class="topbar-profile-wrapper">
        <button type="button" class="topbar-profile" id="topbarProfileButton">

            <div class="topbar-avatar">
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto {{ Auth::user()->name }}">
                @else
                    <div class="topbar-avatar-default">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="topbar-profile-text">
                <strong>{{ Auth::user()->name }}</strong>
                <span>{{ ucfirst(Auth::user()->role) }}</span>
            </div>

            <i class="bi bi-chevron-down topbar-chevron"></i>
        </button>

        <!-- DROPDOWN -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="#">
                <i class="bi bi-person"></i>
                <span>Profil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>