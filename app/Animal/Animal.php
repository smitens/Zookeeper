<?php
namespace Zooapp\App\Animals;

class Animal
{
    private string $name;
    private string $foodPreference;
    private int $happiness;
    private int $satiety;

    const MIN_HAPPINESS = 0;
    const MAX_HAPPINESS = 100;
    const MIN_SATIETY = 0;
    const MAX_SATIETY = 100;

    public function __construct(string $name, string $foodPreference)
    {
        $this->name = $name;
        $this->foodPreference = $foodPreference;
        $this->happiness = self::MAX_HAPPINESS / 2;
        $this->satiety = self::MAX_SATIETY / 2;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFoodPreference(): string
    {
        return $this->foodPreference;
    }

    public function getHappiness(): int
    {
        return $this->happiness;
    }

    public function getSatiety(): int
    {
        return $this->satiety;
    }


    public function setHappiness(int $happiness): void
    {
        $this->happiness = max(self::MIN_HAPPINESS, min(self::MAX_HAPPINESS, $happiness));
    }

    public function setSatiety(int $satiety): void
    {
        $this->satiety = max(self::MIN_SATIETY, min(self::MAX_SATIETY, $satiety));
    }


    public function feed(string $food): string
    {
        if ($this->satiety >= self::MAX_SATIETY) {
            return "{$this->name} is already fully satiated.";
        }

        if ($food === $this->foodPreference) {
            $this->setSatiety($this->satiety + 5);
            $this->setHappiness($this->happiness + 5);
            return "Fed {$this->name} with $food. Happiness and Satiety increased.";
        } else {
            $this->setSatiety($this->satiety - 5);
            $this->setHappiness($this->happiness - 10);
            return "Fed {$this->name} with $food. It's not their preferred food. Happiness and Satiety decreased.";
        }
    }


    public function pet(int $duration): string
    {
        if ($this->satiety <= self::MIN_SATIETY) {
            return "{$this->name} is dying from hunger!";
        }

        if ($this->happiness >= self::MAX_HAPPINESS) {
            return "{$this->name} is overexcited!";
        }

        $this->setHappiness($this->happiness + ($duration * 5));
        $this->setSatiety($this->satiety - ($duration * 5));
        return "Petted {$this->name} for $duration seconds.";
    }


    public function play(int $duration): string
    {
        if ($this->satiety <= self::MIN_SATIETY) {
            return "{$this->name} is dying from hunger!";
        }

        if ($this->happiness >= self::MAX_HAPPINESS) {
            return "{$this->name} is overexcited!";
        }

        $this->setHappiness($this->happiness + ($duration * 5));
        $this->setSatiety($this->satiety - ($duration * 5));
        return "Played with {$this->name} for $duration seconds.";
    }


    public function work(int $duration): string
    {
        if ($this->satiety <= self::MIN_SATIETY) {
            return "{$this->name} is dying from hunger!";
        }

        $this->setHappiness($this->happiness - ($duration * 5));
        $this->setSatiety($this->satiety - ($duration * 5));
        return "Made {$this->name} work for $duration seconds.";
    }
}