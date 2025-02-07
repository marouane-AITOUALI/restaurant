<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/api/weather", name="api_weather")
     */
    #[Route(path: '/api/weather', name: 'api_weather')]
    public function getWeather(CacheInterface $cache): JsonResponse
    {
        $apiKey = $_ENV['OPENWEATHER_API_KEY'];
        $city = 'Paris';
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$apiKey}&lang=fr";

        $data = $cache->get('weather_data', function (ItemInterface $item) use ($url) {
            $item->expiresAfter(3000); // Cache pour 50 minutes
            $response = $this->httpClient->request('GET', $url, [
                'verify_peer' => false,
                'verify_host' => false,
            ]);
            return $response->toArray();
        });

        return $this->json([
            'temp' => $data['main']['temp'],
            'description' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon'],
        ]);
    }
}
