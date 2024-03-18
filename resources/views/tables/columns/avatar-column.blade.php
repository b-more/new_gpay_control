<div class="w-10 h-10">
    @if(str_starts_with($getRecord()->avatar, "https://"))
        <div  class="w-10 h-10 rounded-full border-2 border-green-900">
            <img src="{{ $getRecord()->avatar }}" class="w-10 h-10 rounded-full object-cover">
        </div>
    @elseif(str_starts_with($getRecord()->avatar, "avatar_"))
        <div class="w-10 h-10 rounded-full border-2 border-green-900">
            <img src="{{ url('storage/'.$getRecord()->avatar) }}" class="w-10 h-10 rounded-full object-cover">
        </div>
    @elseif(str_starts_with($getRecord()->avatar, "profile"))
        <div class="w-10 h-10 rounded-full border-2 border-green-900">
            <img src="{{ url('storage/'.$getRecord()->avatar) }}" class="w-10 h-10 rounded-full object-cover">
        </div>
    @endif
</div>
