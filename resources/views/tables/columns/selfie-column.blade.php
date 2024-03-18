<div class="w-10 h-10">
    @if(str_starts_with($getRecord()->selfie, "https://"))
        <div  class="w-10 h-10 rounded border-2 border-green-900">
            <img src="{{ $getRecord()->selfie }}" class="w-10 h-10 rounded object-cover">
        </div>
    @elseif(str_starts_with($getRecord()->selfie, "selfie"))
        <div class="w-10 h-10 rounded border-2 border-green-900">
            <img src="{{ url('storage/'.$getRecord()->selfie) }}" class="w-10 h-10 rounded object-cover">
        </div>
    @endif
</div>
