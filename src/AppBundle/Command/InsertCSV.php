<?php

namespace App\AppBundle\Command;

use App\Entity\User;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CsvImportCommand
 * @package AppBundle\ConsoleCommand
 */
class InsertCSV extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * CsvImportCommand constructor.
     *
     * @param EntityManagerInterface $em
     *
     * @throws LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }
    /**
     * Configure
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Imports the mock CSV data file')
        ;
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import en cours...');
        $reader = Reader::createFromPath('%kernel.root_dir%/../src/AppBundle/Data/MOCK_DATA.csv');
        $results = $reader->fetchAssoc();
        $io->progressStart(iterator_count($results));
        foreach ($results as $row) {
            $user = (new User())
                ->setNom($row['nom'])
                ->setPrenom($row['prenom'])
                ->setCampus($row['campus_id'])
                ->setUsername($row['username'])
                ->setTelephone($row['telephone'])
                ->setRoles($row['roles'])
                ->setMail($row['mail'])
                ->setActif($row['actif'])
                ->setAdministrateur($row['administrateur'])
                ->setPassword($row['password'])
            ;
            $this->em->persist($user);
            $io->progressAdvance();
        }
        $this->em->flush();
        $io->progressFinish();
        $io->success('Command executer avec succès!');
    }
}
