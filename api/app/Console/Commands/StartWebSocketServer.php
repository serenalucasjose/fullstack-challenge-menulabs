<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

use Illuminate\Support\Facades\Cache;

use App\Jobs\WeatherRequestJob;

use App\Models\User;

use App\Events\WeatherUpdate;

use Carbon\Carbon;

class StartWebSocketServer extends Command
{
    protected $signature = 'start-weather-service';

    protected $description = 'Starts the WebSocket server to broadcast weather updates';

    protected $users = [];

    public function handle()
    {
        // Start the WebSockets server in a detached process
        $this->info('Weather service: booting up... ');
        exec('php artisan websockets:serve > /dev/null 2>&1 &');

        // Start queue
        exec('php artisan queue:work > /dev/null 2>&1 &');
                
        // Get all weather related info for users
        $this->users = User::all();
        
        // Initialize first serve data
        $this->updateUsersWeatherData();

        $this->info('Weather service ready to accept connections.');

        // Start
        while(true) {
            $this->updateUsersWeatherData();
            sleep(3);
        }
    }

    public function updateUsersWeatherData() {
        foreach ($this->users as $user) {
            // Get data from Cache before dispatching any unncessary Jobs
            $cacheKey = "weather:user:{$user->id}";
            $weatherData = Cache::store('redis')->get($cacheKey);

            if (!$weatherData) {
                WeatherRequestJob::dispatch($user);
            }
        }
    }
}