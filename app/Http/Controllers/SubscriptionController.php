<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SubscriptionController extends Controller
{
    public function showForm()
    {
        return view('subscribe');
    }

    public function subscribe(Request $request)
    {
        $topic = $request->input('topic');
        $callback = $request->input('callback');

        // Send a subscription request to the WebSub hub
        $client = new Client();
        $response = $client->post($topic, [
            'form_params' => [
                'hub.mode' => 'subscribe',
                'hub.callback' => $callback,
                'hub.verify' => 'sync',
            ],
        ]);

        dd($response);


        // Process the response and handle errors if needed

        return redirect()->back()->with('success', 'Subscription successful');
    }
}
