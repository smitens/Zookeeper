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

    public function feed(string $food): string
    {
        if ($food === $this->foodPreference) {
            $this->satiety = min($this->satiety + 5, self::MAX_SATIETY);
            $this->happiness = min($this->happiness + 5, self::MAX_HAPPINESS);
            $message = "Fed {$this->name} with $food. Happiness and Satiety increased.";
        } else {
            $this->satiety = max($this->satiety - 5, self::MIN_SATIETY);
            $this->happiness = max($this->happiness - 10, self::MIN_HAPPINESS);
            $message = "Fed {$this->name} with $food. It's not their preferred food. Happiness and Satiety decreased.";
        }

        return $message;
    }

    public function pet(int $duration): void
    {
        $this->happiness = min($this->happiness + ($duration * 5), self::MAX_HAPPINESS);
        $this->satiety = max($this->satiety - ($duration * 5), self::MIN_SATIETY);
    }

    public function play(int $duration): void
    {
        $this->happiness = min($this->happiness + ($duration * 5), self::MAX_HAPPINESS);
        $this->satiety = max($this->satiety - ($duration * 5), self::MIN_SATIETY);
    }

    public function work(int $duration): void
    {
        $this->happiness = max($this->happiness - ($duration * 5), self::MIN_HAPPINESS);
        $this->satiety = max($this->satiety - ($duration * 5), self::MIN_SATIETY);
    }
}
