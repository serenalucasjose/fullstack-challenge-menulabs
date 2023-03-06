<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

use App\Events\WeatherUpdate;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Cache;

class WeatherRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lat = $this->user->latitude;
        $lng = $this->user->longitude;

        // Make the API request\
        $reqUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&lat=$lat&lon=$lng&appid=".\Config::get('app.open_weather_api_key');
        $response = Http::get($reqUrl);

        // Store the weather data in the database or cache (1 minute)
        $weather = $response->body();
        $cacheKey = "weather:user:{$this->user->id}";
        
        Cache::store('redis')->put($cacheKey, $weather, now()->addMinutes(1));

        broadcast(new WeatherUpdate([
            "user" =>json_decode($this->user),
            "id" => $this->user->id,
            "weather" => json_decode($weather)
        ]));
    }
}
