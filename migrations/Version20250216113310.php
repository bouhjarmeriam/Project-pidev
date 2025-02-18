<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216113310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // Only add the column if it doesn't exist already
    if (!$schema->getTable('consultation')->hasColumn('status')) {
        $this->addSql('ALTER TABLE consultation ADD status VARCHAR(50) DEFAULT "En cours de traitement"');
    }
}

public function down(Schema $schema): void
{
    // Drop the column if it exists
    if ($schema->getTable('consultation')->hasColumn('status')) {
        $this->addSql('ALTER TABLE consultation DROP COLUMN status');
    }
}

    

    
    
}
