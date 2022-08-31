<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716192929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD first_name VARCHAR(255) NOT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD code_postal VARCHAR(255) DEFAULT NULL, ADD tel VARCHAR(255) NOT NULL, CHANGE rue rue VARCHAR(255) DEFAULT NULL, CHANGE pays pays VARCHAR(255) DEFAULT NULL, CHANGE ville last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD ville VARCHAR(255) NOT NULL, DROP last_name, DROP first_name, DROP city, DROP code_postal, DROP tel, CHANGE rue rue VARCHAR(255) NOT NULL, CHANGE pays pays VARCHAR(255) NOT NULL');
    }
}
