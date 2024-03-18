<div>
    @if($getRecord()->payment_channel == "Airtel Money")
        <div class="w-10 h-10 rounded">
            <img src="{{ secure_asset('imgz/airtel.png') }}" class="w-10 h-10 rounded object-cover">
        </div>
    @elseif($getRecord()->payment_channel == "MTN Money")
        <div class="w-10 h-10 rounded">
            <img src="{{ secure_asset('imgz/mtn.png') }}" class="w-10 h-10 rounded object-cover">
        </div>
    @elseif($getRecord()->payment_channel == "Zamtel Money")
        <div class="w-10 h-10 rounded">
            <img src="{{ secure_asset('imgz/zamtel.png') }}" class="w-10 h-10 rounded object-cover">
        </div>
    @elseif($getRecord()->payment_channel == "Unknown")
        <div class="w-10 h-10 rounded">
            <img src="{{ secure_asset('imgz/network_error.png') }}" class="w-10 h-10 rounded object-cover">
        </div>
    @endif
</div>
