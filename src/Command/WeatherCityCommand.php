<?php

namespace App\Command;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Show the weather forecast for a city by country code and city name',
)]
class WeatherCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        $io->writeln(sprintf('Location: %s, %s', $city, $country));

        if ($measurements === []) {
            $io->writeln('No forecast available.');

            return Command::SUCCESS;
        }

        $io->table(
            ['Date', 'Temperature', 'Humidity', 'Pressure', 'Cloudiness', 'Precipitation', 'Wind speed', 'Wind direction'],
            array_map(fn (Measurement $m) => [
                $m->getDate()->format('Y-m-d'),
                $m->getCelsius() . '°C',
                $m->getHumidity() . '%',
                $m->getPressure() . ' hPa',
                $m->getCloudiness() . '%',
                $m->getPrecipitation() . ' mm',
                $m->getWindSpeed() . ' m/s',
                $m->getWindDirection() . '°',
            ], $measurements)
        );

        return Command::SUCCESS;
    }
}