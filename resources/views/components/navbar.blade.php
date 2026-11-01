<!-- Navigation -->
<nav {{ $attributes->merge(['id' => 'mainNav', 'class' => 'navbar navbar-expand-lg navbar-dark fixed-top']) }}>
    <div class="container">
        <a class="navbar-brand" href="#page-top"><img src="{{ asset('img/agency/agency-cms-logo.svg') }}" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            {{ __('Menu') }} <i class="fas fa-bars ms-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link" href="#services">{{ __('Services') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#portfolio">{{ __('Portfolio') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">{{ __('About') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#team">{{ __('Team') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">{{ __('Contact') }}</a></li>
            </ul>
        </div>
    </div>
</nav>
