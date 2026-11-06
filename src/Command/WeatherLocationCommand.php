<?php

namespace App\Command;

use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Show the weather forecast for a location by its ID',
)]
class WeatherLocationCommand
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);
        if ($location === null) {
            $io->error(sprintf('Location with ID %d not found.', $id));

            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));

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

