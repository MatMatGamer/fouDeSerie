<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010132719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, drapeau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE serie ADD pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE serie ADD CONSTRAINT FK_AA3A9334A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_AA3A9334A6E44244 ON serie (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serie DROP FOREIGN KEY FK_AA3A9334A6E44244');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP INDEX IDX_AA3A9334A6E44244 ON serie');
        $this->addSql('ALTER TABLE serie DROP pays_id');
    }
}
