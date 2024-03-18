<div>
    @if($getRecord()->short_url == "")
        <div></div>
    @else
    <a href="{{ $getRecord()->short_url }}" target="_blank" class="flex flex-row items-center space-x-2">
        <div class="text-blue-700 hover:text-green-600 hover:font-semibold text-sm">Open Link</div>
        <div class="text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
        </div>
    </a>
    @endif
</div>
