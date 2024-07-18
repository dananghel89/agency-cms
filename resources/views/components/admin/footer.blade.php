<!-- Footer -->
<footer {{ $attributes->merge(['class' => 'sticky-footer bg-white']) }}>
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>{!! __('Copyright &copy; :website :year', ['website' => config('sb.admin_title'), 'year' => date('Y')]) !!}</span>
        </div>
    </div>
</footer>
