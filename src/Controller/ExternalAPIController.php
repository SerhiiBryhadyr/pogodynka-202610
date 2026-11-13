<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ExternalAPIController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api', methods: ['GET', 'POST'])]
    public function index(Request $request, HttpClientInterface $httpClient): Response
    {
        $form = $this->createForm(ExternalAPIType::class);
        $form->handleRequest($request);

        $weatherData = null;
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $latitude = $form->get('latitude')->getData();
            $longitude = $form->get('longitude')->getData();

            try {
                $response = $httpClient->request('GET', 'https://api.open-meteo.com/v1/forecast', [
                    'query' => [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'daily' => implode(',', [
                            'temperature_2m_max',
                            'temperature_2m_min',
                            'relative_humidity_2m_mean',
                            'pressure_msl_mean',
                            'cloud_cover_mean',
                            'precipitation_sum',
                            'wind_speed_10m_max',
                            'wind_direction_10m_dominant',
                        ]),
                        'wind_speed_unit' => 'ms',
                        'timezone' => 'UTC',
                    ],
                ]);
                $weatherData = $response->toArray();
            } catch (ExceptionInterface) {
                $error = 'Could not fetch the forecast from Open-Meteo.';
            }
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form,
            'weather_data' => $weatherData,
            'error' => $error,
        ]);
    }
}
