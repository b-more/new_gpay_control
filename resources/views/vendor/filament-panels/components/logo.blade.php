<div class="py-4">
    @if(request()->routeIs('filament.admin.auth.login') || request()->routeIs('filament.admin.auth.password-reset.request'))
        <img src="{{ url('imgz/geepay_logo_green.png') }}" class="h-10">
    @else
        <img src="{{ url('imgz/geepay_logo_white.png') }}" class="h-10">
    @endif
</div>
