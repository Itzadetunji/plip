<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\MonoWebhookJob;
use App\Models\WebhookUpdates;
use App\Responders\ApiResponse;
use Illuminate\Http\Request;

class MonoWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        info(json_encode($request->all(), JSON_PRETTY_PRINT));
        WebhookUpdates::create([
            "event_type" => $request->all()["event"],
            "dump" => collect($request->all())
        ]);

        dispatch(new MonoWebhookJob($request->all()));

        return ApiResponse::noContent();
    }
}
