<?php

namespace App\Command;

use App\Repository\MediaRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReservationCleanupCommand extends Command
{
    private $MediaRepository;

    protected static $defaultName = 'app:reservation:cleanup';

    public function __construct(MediaRepository $MediaRepository)
    {
        $this->MediaRepository = $MediaRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setDescription('Deletes old Reservations')
        ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
        $io->note('Dry mode enabled');

        $count = $this->MediaRepository->countNotPicked();
    } else {
        $count = $this->MediaRepository->deleteNotPicked();
        }
        $io->success(sprintf('Deleted "%d" old Reservations', $count));

    return 0;
    }
}
