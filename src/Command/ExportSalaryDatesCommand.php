<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\CsvService;
use App\Service\SalaryDateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportSalaryDatesCommand
 *
 * @package App\Command
 */
class ExportSalaryDatesCommand extends Command
{
    protected static $defaultName = 'app:export:salarydates';

    /**
     * @var SalaryDateService
     */
    private $salaryDateService;

    /**
     * @var CsvService
     */
    private $csvService;

    /**
     * ExportSalaryDatesCommand constructor.
     *
     * @param SalaryDateService $salaryDateService
     * @param CsvService        $csvService
     */
    public function __construct(
        SalaryDateService $salaryDateService,
        CsvService $csvService
    ) {
        $this->salaryDateService = $salaryDateService;
        $this->csvService        = $csvService;

        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Exports the salarydates for the next x or 12 months')
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'Output filepath'
            )
            ->addArgument(
                'months',
                InputArgument::OPTIONAL,
                'Number of months, default: 12',
                12
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputFile   = (string)$input->getArgument('output');
        $monthCounter = (int)$input->getArgument('months');
        $date         = (new \DateTime)->modify('first day -1 month');
        $data       = [];

        for ($i = 1; $i <= $monthCounter; $i++) {
            $bonusDate = $this->salaryDateService
                ->jumpToNextBonusSalaryDate($date)
                ->format('j');
            $this->salaryDateService->jumpToNextSalaryDate($date);
            $data[] = [
                'month'        => $date->format('l'),
                'salaray_date' => $date->format('j'),
                'bonus_date'   => $bonusDate,
            ];
        }

        $filePath = $this->csvService->write($outputFile, $data);
        $output->writeln($filePath);

        return 0;
    }
}
