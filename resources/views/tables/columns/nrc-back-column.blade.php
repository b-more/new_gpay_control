<div class="w-10 h-10">
    @if(str_starts_with($getRecord()->nrc_back, "https://"))
        <div  class="w-10 h-10 rounded border-2 border-green-900">
            <img src="{{ $getRecord()->nrc_back }}" class="w-10 h-10 rounded object-cover">
        </div>
    @elseif(str_starts_with($getRecord()->nrc_back, "nrc"))
        <div class="w-10 h-10 rounded border-2 border-green-900">
            <img src="{{ url('storage/'.$getRecord()->nrc_back) }}" class="w-10 h-10 rounded object-cover">
        </div>
    @endif
</div>
