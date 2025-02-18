<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-patient',
    description: 'Creates a new patient user',
)]
class CreatePatientCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $user->setEmail('patient1@test.com');
        $user->setRoles(['ROLE_PATIENT']);
        
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'test123');
        $user->setPassword($hashedPassword);
        
        $user->setNomUser('Patient');
        $user->setPrenomUser('Test');
        $user->setNumTelephone('12345678');
        $user->setSexe('M');
        $user->setAddresse('123 Patient St');
        $user->setDateNaissance(new \DateTime('1990-01-01'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Patient created successfully!');

        return Command::SUCCESS;
    }
}
