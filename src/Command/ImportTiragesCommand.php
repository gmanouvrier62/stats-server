<?php

namespace App\Command;

use App\Entity\Tirages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-tirages',
    description: 'Add a short description for your command',
)]
class ImportTiragesCommand extends Command
{
    protected static $defaultName = 'app:import-tirages';
    private EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        // Injection de dépendances du gestionnaire d'entités
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
         $io = new SymfonyStyle($input, $output);

        // Rechercher le tirage le plus récent
        $tirageRepository = $this->entityManager->getRepository(Tirages::class);
        $latestTirage = $tirageRepository->findOneBy([], ['tir_date' => 'DESC']);

        if ($latestTirage) {
            $io->success('Le tirage le plus récent a eu lieu le : ' . $latestTirage->getTirDate()->format('Y-m-d H:i:s') . "---" .
                $latestTirage->getTir1() . "\n" . 
                $latestTirage->getTir2() . "\n" . 
                $latestTirage->getTir3() . "\n" . 
                $latestTirage->getTir4() . "\n" . 
                $latestTirage->getTir5() . "\n" . 
                "(" . $latestTirage->getTir2() . ")\n");
        } else {
            $io->warning('Aucun tirage trouvé dans la base de données.');
        }

        return Command::SUCCESS;
    }
}
