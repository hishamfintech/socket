<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessWebhookJob;

class WebSubController extends Controller
{
    public function __invoke(Request $request)
    {
        ProcessWebhookJob::dispatch($request);

        return response()->json(['message' => 'WebSub notification received.'], 200);
    }
}
