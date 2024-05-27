<?php

namespace Zooapp\App\Zookeeper;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Carbon\Carbon;
use Zooapp\App\Animals\Animal;

class Zookeeper
{
    private array $animals;
    private array $sessionLog;

    public function __construct()
    {
        $this->animals = [
            new Animal('Python', 'meat'),
            new Animal('Tarantula', 'insects'),
            new Animal('Monkey', 'fruit'),
            new Animal('Penguin', 'fish')
        ];
        $this->sessionLog = [];
    }

    public function run()
    {
        echo "Welcome to Zookeeper!\n";

        while (true) {
            echo "Available animals: Python, Tarantula, Monkey, Penguin\n";
            echo "Available actions: feed, pet, play, work\n";

            $this->displayAnimalStatus();

            $animalName = readline('Enter the name of the animal: ');
            $animal = $this->findAnimal($animalName);

            if (!$animal) {
                echo "Animal not found. Available animals: Python, Tarantula, Monkey, Penguin\n";
                continue;
            }

            $action = readline('Enter the action to perform (feed, pet, play, work): ');

            if (!in_array($action, ['feed', 'pet', 'play', 'work'])) {
                echo "Invalid action\n";
                continue;
            }

            $food = null;
            $duration = null;

            if ($action === 'feed') {
                $food = readline('Enter the food to feed the animal: ');
                if ($animal->getFoodPreference() !== $food) {
                    echo "Please provide {$animal->getName()}'s preferred food: {$animal->getFoodPreference()}\n";
                    $food = readline('Enter the food to feed the animal: ');
                }
            } else {
                $duration = intval(readline('Enter the duration (in minutes): '));
            }

            switch ($action) {
                case 'feed':
                    $message = $animal->feed($food);
                    echo "$message\n";
                    $this->logAction($message);
                    break;
                case 'pet':
                    $animal->pet($duration);
                    $this->logAction("Petted {$animal->getName()} for $duration minutes.");
                    break;
                case 'play':
                    $animal->play($duration);
                    $this->logAction("Played with {$animal->getName()} for $duration minutes.");
                    break;
                case 'work':
                    $animal->work($duration);
                    $this->logAction("Made {$animal->getName()} work for $duration minutes.");
                    break;
            }

            echo "Action: $action on {$animal->getName()}\n";
            $this->displayAnimalStatus();

            $continue = readline('Do you want to continue? (yes/no): ');
            if (strtolower($continue) !== 'yes') {
                echo "Thank you for taking care!\n";
                $this->printSessionLog();
                break;
            }
        }
    }

    private function findAnimal(string $name): ?Animal
    {
        foreach ($this->animals as $animal) {
            if (strtolower($animal->getName()) === strtolower($name)) {
                return $animal;
            }
        }
        return null;
    }

    private function displayAnimalStatus(): void
    {
        $output = new ConsoleOutput();

        $table = new Table($output);
        $table->setHeaders(['Animal', 'Food Preference', 'Happiness', 'Satiety']);

        foreach ($this->animals as $animal) {
            $happinessValue = $animal->getHappiness();
            $happinessCell = $happinessValue < 50 ? "<fg=red>$happinessValue</>" : "<fg=green>$happinessValue</>";
            $satietyValue = $animal->getSatiety();
            $satietyCell = $satietyValue < 50 ? "<fg=red>$satietyValue</>" : "<fg=green>$satietyValue</>";
            $table->addRow([
                $animal->getName(),
                $animal->getFoodPreference(),
                $happinessCell,
                $satietyCell,
            ]);
        }
        $table->render();
    }

    private function logAction(string $message): void
    {
        $this->sessionLog[] = $message;
        $now = Carbon::now()->toDateTimeString();
        file_put_contents('zookeeper.log', "[$now] $message\n", FILE_APPEND);
    }

    private function printSessionLog(): void
    {
        echo "\nInformation on how you have treated the animals:\n";
        foreach ($this->sessionLog as $logEntry) {
            echo "$logEntry\n";
        }
    }
}