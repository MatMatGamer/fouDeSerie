<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013092349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serie_genre (idPays INT NOT NULL, idGenre INT NOT NULL, INDEX IDX_4B5C076C47626230 (idPays), INDEX IDX_4B5C076C949470E5 (idGenre), PRIMARY KEY(idPays, idGenre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE serie_genre ADD CONSTRAINT FK_4B5C076C47626230 FOREIGN KEY (idPays) REFERENCES serie (id)');
        $this->addSql('ALTER TABLE serie_genre ADD CONSTRAINT FK_4B5C076C949470E5 FOREIGN KEY (idGenre) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serie_genre DROP FOREIGN KEY FK_4B5C076C47626230');
        $this->addSql('ALTER TABLE serie_genre DROP FOREIGN KEY FK_4B5C076C949470E5');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE serie_genre');
    }
}
