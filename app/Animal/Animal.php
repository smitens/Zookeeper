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
        if ($food === $this->foodPreference) {
            $this->setSatiety($this->satiety + 10);
            $this->setHappiness($this->happiness + 5);
            $this->validateStateAfterAction();
            return "Fed {$this->name} with $food. Happiness and Satiety increased.";
        } else {
            $this->setSatiety($this->satiety - 10);
            $this->setHappiness($this->happiness - 10);
            $this->validateStateAfterAction();
            return "Fed {$this->name} with $food. It's not their preferred food. Happiness and Satiety decreased.";
        }
    }

    public function pet(int $duration): string
    {
        $newHappiness = $this->happiness + ($duration * 5);
        if ($newHappiness > self::MAX_HAPPINESS) {
            return "{$this->name} will become overexcited if it's petted for that long.";
        }

        $this->setHappiness($newHappiness);
        $this->setSatiety($this->satiety - ($duration * 5));
        $this->validateStateAfterAction();
        return "Petted {$this->name} for $duration minutes.";
    }

    public function play(int $duration): string
    {
        $newHappiness = $this->happiness + ($duration * 5);
        if ($newHappiness > self::MAX_HAPPINESS) {
            return "{$this->name} will become overexcited if it plays for that long.";
        }

        $this->setHappiness($newHappiness);
        $this->setSatiety($this->satiety - ($duration * 5));
        $this->validateStateAfterAction();
        return "Played with {$this->name} for $duration minutes.";
    }

    public function work(int $duration): string
    {
        $newHappiness = $this->happiness - ($duration * 5);
        if ($newHappiness < self::MIN_HAPPINESS) {
            return "{$this->name} will become overexcited if it works for that long.";
        }

        $this->setHappiness($newHappiness);
        $this->setSatiety($this->satiety - ($duration * 5));
        $this->validateStateAfterAction();
        return "Made {$this->name} work for $duration minutes.";
    }

    private function validateStateAfterAction(): void
    {
        if ($this->happiness >= self::MAX_HAPPINESS) {
            $this->setHappiness(self::MAX_HAPPINESS);
            echo "\e[1;31m{$this->name} is overexcited! Treat {$this->name} accordingly!\e[0m\n";
        }

        if ($this->happiness <= self::MIN_HAPPINESS) {
            $this->setHappiness(self::MIN_HAPPINESS);
            echo "\e[1;31m{$this->name} is very unhappy! Treat {$this->name} accordingly!\e[0m\n";
        }

        if ($this->satiety >= self::MAX_SATIETY) {
            $this->setSatiety(self::MAX_SATIETY);
            echo "\e[1;31m{$this->name} is already fully satiated. Treat {$this->name} accordingly!\e[0m\n";
        }

        if ($this->satiety <= self::MIN_SATIETY) {
            $this->setSatiety(self::MIN_SATIETY);
            echo "\e[1;31m{$this->name} is dying from hunger! Treat {$this->name} accordingly!\e[0m\n";
        }
    }
}