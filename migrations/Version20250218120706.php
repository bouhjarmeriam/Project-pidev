<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218120706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier_medicale (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, medecin_id INT NOT NULL, responsable_administratif_id INT NOT NULL, date_de_creation DATETIME NOT NULL, historique_des_maladies LONGTEXT DEFAULT NULL, operations_passees LONGTEXT DEFAULT NULL, consultations_passees LONGTEXT DEFAULT NULL, statut_dossier VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_4C53FBC06B899279 (patient_id), INDEX IDX_4C53FBC04F31A84 (medecin_id), INDEX IDX_4C53FBC07B51CD71 (responsable_administratif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medecin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1BDA53C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_de_naissance DATE NOT NULL, sexe VARCHAR(10) NOT NULL, telephone INT NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1ADAD7EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable_administratif (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, service VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, horaire_travail VARCHAR(255) NOT NULL, responsabilites VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sejour (id INT AUTO_INCREMENT NOT NULL, dossier_medicale_id INT NOT NULL, date_entree DATETIME NOT NULL, date_sortie DATETIME NOT NULL, type_sejour VARCHAR(255) NOT NULL, frais_sejour DOUBLE PRECISION NOT NULL, moyen_paiement VARCHAR(255) NOT NULL, statut_paiement VARCHAR(255) NOT NULL, prix_extras DOUBLE PRECISION DEFAULT NULL, INDEX IDX_96F52028F2C46B04 (dossier_medicale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_medicale ADD CONSTRAINT FK_4C53FBC06B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE dossier_medicale ADD CONSTRAINT FK_4C53FBC04F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id)');
        $this->addSql('ALTER TABLE dossier_medicale ADD CONSTRAINT FK_4C53FBC07B51CD71 FOREIGN KEY (responsable_administratif_id) REFERENCES responsable_administratif (id)');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sejour ADD CONSTRAINT FK_96F52028F2C46B04 FOREIGN KEY (dossier_medicale_id) REFERENCES dossier_medicale (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier_medicale DROP FOREIGN KEY FK_4C53FBC06B899279');
        $this->addSql('ALTER TABLE dossier_medicale DROP FOREIGN KEY FK_4C53FBC04F31A84');
        $this->addSql('ALTER TABLE dossier_medicale DROP FOREIGN KEY FK_4C53FBC07B51CD71');
        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C6A76ED395');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE sejour DROP FOREIGN KEY FK_96F52028F2C46B04');
        $this->addSql('DROP TABLE dossier_medicale');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE responsable_administratif');
        $this->addSql('DROP TABLE sejour');
        $this->addSql('DROP TABLE user');
    }
}
