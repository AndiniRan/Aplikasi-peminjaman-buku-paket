<nav x-data="{ open: false }" class="library-navbar">
    <div class="library-container">
        <div class="navbar-inner">
            <!-- {{-- Logo --}} -->
            <div class="navbar-left">
                <div class="library-logo-wrapper">
                    <a href="{{ url('/') }}" class="library-logo">
                        <div class="logo-book">
                            <img src="{{ asset('images/logo-perpustakaan.png') }}" alt="Logo Perpustakaan">
                        </div>
                        <span>Perpustakaan</span>
                    </a>
                </div>
            </div>

            <!-- {{-- Login --}} -->
            @if (request()->routeIs('login') === false)
                <div class="library-login-wrapper">
                    <a href="{{ route('login') }}" class="login-button">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>