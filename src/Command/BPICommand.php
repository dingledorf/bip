<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BPICommand extends Command
{
    private const S0 = '0';
    private const S1 = '1';
    private const S2 = '2';

    private string $currentState;
    private array $transitions;

    /**
     * BPICommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->currentState = "S0";
        $this->transitions = [
            "S0" => ["0" => "S0", "1" => "S1"],
            "S1" => ["0" => "S2", "1" => "S0"],
            "S2" => ["0" => "S1", "1" => "S2"]
        ];
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('FSM')
            ->addArgument('input', InputArgument::REQUIRED, 'What\'s the input?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $inputString = trim($input->getArgument('input'));
            $output->writeln("Your Input: " . $inputString);
            for($i = 0; $i < strlen($inputString); $i++) {
                $this->currentState = $this->stateTransition($this->currentState, $inputString[$i]);
            }
            $output->writeln("Output: " . $this->getOutput($this->currentState));
        } catch (\Exception $e) {
            $output->writeln("An error occurred: " . $e->getMessage());
            throw $e;
        }
        return 0;
    }

    /**
     * @param $state
     * @param $input
     * @return mixed|string
     * @throws \Exception
     */
    public function stateTransition($state, $input)
    {
        if(isset($this->transitions[$state])) {
            if(isset($this->transitions[$state][$input])) {
                return $this->transitions[$state][$input];
            }
            else {
                throw new \Exception("Invalid input {$input} for state {$state}");
            }
        }
        else {
            throw new \Exception("Invalid state {$state}!");
        }

    }

    /**
     * @param $state
     * @return string
     * @throws \Exception
     */
    public static function getOutput($state)
    {
        switch($state) {
            case 'S0':
                return self::S0;
                break;
            case 'S1':
                return self::S1;
                break;
            case 'S2':
                return self::S2;
                break;
            default:
                throw new \Exception("Invalid state {$state}!");
        }
    }
}