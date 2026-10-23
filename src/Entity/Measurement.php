<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $celsius = null;

    #[ORM\Column]
    private ?int $humidity = null;

    #[ORM\Column]
    private ?int $pressure = null;

    #[ORM\Column]
    private ?int $cloudiness = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 1)]
    private ?string $precipitation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 1)]
    private ?string $windSpeed = null;

    #[ORM\Column]
    private ?int $windDirection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCelsius(): ?string
    {
        return $this->celsius;
    }

    public function setCelsius(string $celsius): static
    {
        $this->celsius = $celsius;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(int $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getPressure(): ?int
    {
        return $this->pressure;
    }

    public function setPressure(int $pressure): static
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getCloudiness(): ?int
    {
        return $this->cloudiness;
    }

    public function setCloudiness(int $cloudiness): static
    {
        $this->cloudiness = $cloudiness;

        return $this;
    }

    public function getPrecipitation(): ?string
    {
        return $this->precipitation;
    }

    public function setPrecipitation(string $precipitation): static
    {
        $this->precipitation = $precipitation;

        return $this;
    }

    public function getWindSpeed(): ?string
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(string $windSpeed): static
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDirection(): ?int
    {
        return $this->windDirection;
    }

    public function setWindDirection(int $windDirection): static
    {
        $this->windDirection = $windDirection;

        return $this;
    }
}
