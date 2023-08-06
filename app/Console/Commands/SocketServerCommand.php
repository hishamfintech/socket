<?php

namespace App\Console\Commands;

use App\Service\DataHandler;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Illuminate\Console\Command;
use Ratchet\WebSocket\WsServer;



class SocketServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $app = new HttpServer(
            new WsServer(
                new DataHandler()
            )
        );
        $server = IoServer::factory($app,8080);
        $server->run();
        return 0;
    }
}
