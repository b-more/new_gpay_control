<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PrivateController extends Controller
{
    public function __invoke($file)
    {
        // TODO: Implement __invoke() method.
        abort_if(auth()->guest(), ResponseAlias::HTTP_FORBIDDEN);

        Log::info("loading image ".$file);

        return response()->file(
            Storage::path($file)
        );
    }
}
