<!-- Footer -->
<footer {{ $attributes->merge(['class' => 'footer py-4']) }}>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 text-lg-start">{!! __('Copyright &copy; :website :year', ['website' => option('site_title'), 'year' => date('Y')]) !!}</div>
            <div class="col-lg-4 my-3 my-lg-0">
                <x-social-links :item="option('social_links', true)" />
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="link-dark text-decoration-none me-3" href="javascript:void(0)">{{ __('Privacy Policy') }}</a>
                <a class="link-dark text-decoration-none" href="javascript:void(0)">{{ __('Terms of Use') }}</a>
            </div>
        </div>
    </div>
</footer>
