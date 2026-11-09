<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $util,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^(json|csv)$/'])]
        string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            $response = $this->render("weather_api/index.{$format}.twig", [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
            $response->headers->set(
                'Content-Type',
                $format === 'csv' ? 'text/plain; charset=UTF-8' : 'application/json',
            );

            return $response;
        }

        $rows = array_map(fn(Measurement $m) => [
            'date' => $m->getDate()->format('Y-m-d'),
            'celsius' => (int) $m->getCelsius(),
            'fahrenheit' => $m->getFahrenheit(),
            'humidity' => $m->getHumidity(),
            'pressure' => $m->getPressure(),
            'cloudiness' => $m->getCloudiness(),
            'precipitation' => (float) $m->getPrecipitation(),
            'wind_speed' => (float) $m->getWindSpeed(),
            'wind_direction' => $m->getWindDirection(),
        ], $measurements);

        if ($format === 'csv') {
            $lines = ['city,country,date,celsius,fahrenheit,humidity,pressure,cloudiness,precipitation,wind_speed,wind_direction'];
            foreach ($rows as $row) {
                $lines[] = implode(',', [$city, $country, ...$row]);
            }

            return new Response(implode("\n", $lines) . "\n", Response::HTTP_OK, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => $rows,
        ]);
    }
}

