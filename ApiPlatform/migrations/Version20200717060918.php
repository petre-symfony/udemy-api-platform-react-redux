<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717060918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post ADD updated_at DATETIME NOT NULL, CHANGE published created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment ADD updated_at DATETIME NOT NULL, CHANGE published created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post ADD published DATETIME NOT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE comment ADD published DATETIME NOT NULL, DROP created_at, DROP updated_at');
    }
}
