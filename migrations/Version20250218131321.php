<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218131321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON user');
        $this->addSql('ALTER TABLE user ADD Nom_User VARCHAR(255) NOT NULL, ADD Prenom_User VARCHAR(255) NOT NULL, ADD Num_Telephone VARCHAR(20) NOT NULL, ADD sexe VARCHAR(1) NOT NULL, ADD addresse VARCHAR(255) NOT NULL, ADD Date_Naissance DATE NOT NULL, CHANGE id Id_User INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (Id_User)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user MODIFY Id_User INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON user');
        $this->addSql('ALTER TABLE user DROP Nom_User, DROP Prenom_User, DROP Num_Telephone, DROP sexe, DROP addresse, DROP Date_Naissance, CHANGE Id_User id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (id)');
    }
}
