<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Medecin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-doctor',
    description: 'Creates a new doctor user and medecin record',
)]
class CreateDoctorCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create User
        $user = new User();
        $user->setEmail('medecin@test.com');
        $user->setRoles(['ROLE_MEDECIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'test123'));
        $user->setNomUser('Dr');
        $user->setPrenomUser('Test');
        $user->setNumTelephone('12345678');
        $user->setSexe('M');
        $user->setAddresse('123 Medical St');
        $user->setDateNaissance(new \DateTime('1980-01-01'));

        $this->entityManager->persist($user);
        
        // Create Medecin
        $medecin = new Medecin();
        $medecin->setNom('Dr');
        $medecin->setPrenom('Test');
        $medecin->setSpecialite('Généraliste');
        $medecin->setUser($user);

        $this->entityManager->persist($medecin);
        $this->entityManager->flush();

        $output->writeln('Doctor created successfully!');

        return Command::SUCCESS;
    }
}
