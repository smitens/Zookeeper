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

            $message = '';

            switch ($action) {
                case 'pet':
                case 'play':
                    if ($animal->getHappiness() >= Animal::MAX_HAPPINESS) {
                        $message = "{$animal->getName()} is already overexcited and cannot engage in this activity.";
                    } elseif ($animal->getHappiness() <= Animal::MIN_HAPPINESS) {
                        $message = "{$animal->getName()} is very unhappy and cannot engage in this activity.";
                    }
                    break;
                case 'work':
                    if ($animal->getSatiety() <= Animal::MIN_SATIETY) {
                        $message = "{$animal->getName()} is dying from hunger and cannot engage in this activity.";
                    }
                    break;
                case 'feed':
                    if ($animal->getSatiety() >= Animal::MAX_SATIETY) {
                        $message = "{$animal->getName()} is already fully satiated and cannot engage in this activity.";
                    }
                    break;
            }

            if ($message !== '') {
                echo "$message\n";
                continue;
            }

            if ($action === 'feed') {
                $food = readline('Enter the food to feed the animal: ');
                $message = $animal->feed($food);
            } else {
                $duration = intval(readline('Enter the duration (in minutes): '));
                switch ($action) {
                    case 'pet':
                        $message = $animal->pet($duration);
                        break;
                    case 'play':
                        $message = $animal->play($duration);
                        break;
                    case 'work':
                        $message = $animal->work($duration);
                        break;
                }
            }

            echo "$message\n";
            $this->logAction($message);

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
            $happinessCell = $happinessValue < 10 ? "<fg=red>$happinessValue</>" :
                ($happinessValue < 90 ? "<fg=green>$happinessValue</>" : "<fg=red>$happinessValue</>");
            $satietyValue = $animal->getSatiety();
            $satietyCell = $satietyValue < 10 ? "<fg=red>$satietyValue</>" :
                ($satietyValue < 90 ? "<fg=green>$satietyValue</>" : "<fg=red>$satietyValue</>");
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
        echo "\n\e[1mInformation on how you have treated the animals:\e[0m\n";
        foreach ($this->sessionLog as $logEntry) {
            echo "-$logEntry\n";
        }
    }
}
