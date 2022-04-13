<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413155319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avion CHANGE id_agence id_agence INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis CHANGE Id Id INT DEFAULT NULL, CHANGE RefActivite RefActivite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE idClient idClient INT DEFAULT NULL, CHANGE etat etat VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_client id_client INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avion CHANGE id_agence id_agence INT NOT NULL');
        $this->addSql('ALTER TABLE avis CHANGE RefActivite RefActivite INT NOT NULL, CHANGE Id Id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE etat etat VARCHAR(50) DEFAULT \'0\' NOT NULL, CHANGE idClient idClient INT NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_client id_client INT NOT NULL');
    }
}
