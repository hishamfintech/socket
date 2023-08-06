<?php

use App\Models\Comment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\TopicController;
use App\Http\Controllers\SubscriptionController;
use Spatie\WebhookClient\Http\Controllers\WebhookController;

Route::webhooks('webhook/websub', 'websub');


Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-mail', function () {
    Mail::to('test@example.com')->send(new \App\Mail\TestMail());
    return 'Mail sent';
});
Route::get('websocket',function(){
    return view('websocket');
});
Route::get('/comments/{id}/sse', function ($id) {
    $response = new \Illuminate\Http\Response();
    $response->header('Content-Type', 'text/event-stream');
    $response->header('Cache-Control', 'no-cache');

    while (true) {
        // Get the latest comment for the post with the given ID
        $comment = Comment::where('post_id', $id)->latest()->first();

        if ($comment) {
            // If a new comment was found, send an SSE event to the client
            $data = [
                'event' => 'comment',
                'data' => [
                    'id' => $comment->id,
                    'text' => $comment->text_id,
                    'user_id' => $comment->user_id,
                    'post_id' => $comment->post_id,
                    'created_at' => $comment->created_at,
                ],
            ];

            $response->setContent("data: " . json_encode($data) . "\n\n");
            $response->send();

            // Wait for a short period before checking for new comments again
            usleep(1000000);
        }
    }
});

Route::get('/subscribe', [SubscriptionController::class, 'showForm'])->name('subscribe');
Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);

Route::resource('topic',TopicController::class);
