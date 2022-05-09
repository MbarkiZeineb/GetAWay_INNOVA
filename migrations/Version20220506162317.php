<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506162317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (RefAct INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(50) NOT NULL, Descrip VARCHAR(50) NOT NULL, Duree VARCHAR(50) NOT NULL, NbrPlace INT NOT NULL, Date VARCHAR(100) NOT NULL, Type VARCHAR(50) NOT NULL, Location VARCHAR(50) NOT NULL, Prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(RefAct)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avion (id_avion INT AUTO_INCREMENT NOT NULL, id_agence INT DEFAULT NULL, nbr_place INT NOT NULL, nom_avion VARCHAR(30) NOT NULL, INDEX id_agence (id_agence), PRIMARY KEY(id_avion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (RefAvis INT AUTO_INCREMENT NOT NULL, Message VARCHAR(250) NOT NULL, Date DATE NOT NULL, Rating INT NOT NULL, Id INT DEFAULT NULL, RefActivite INT DEFAULT NULL, INDEX fk_idavis (Id), INDEX frk_act (RefActivite), PRIMARY KEY(RefAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorievoy (idcat INT AUTO_INCREMENT NOT NULL, nomcat VARCHAR(30) NOT NULL, PRIMARY KEY(idcat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id_categ INT AUTO_INCREMENT NOT NULL, nom_categ VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id_categ)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebergement (referance INT AUTO_INCREMENT NOT NULL, id_categ INT DEFAULT NULL, offreur_id INT DEFAULT NULL, paye VARCHAR(15) DEFAULT NULL, adress VARCHAR(50) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, description VARCHAR(300) DEFAULT NULL, photo VARCHAR(999) DEFAULT NULL, date_start DATE DEFAULT NULL, date_end DATE DEFAULT NULL, contact INT DEFAULT NULL, nbr_detoile INT DEFAULT NULL, nbr_suite INT DEFAULT NULL, nbr_parking INT DEFAULT NULL, model_caravane VARCHAR(15) DEFAULT NULL, INDEX fk_off (offreur_id), INDEX fk_categ (id_categ), PRIMARY KEY(referance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, id_reservation INT DEFAULT NULL, modalite_paiement VARCHAR(30) NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX fk_reservation (id_reservation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promostion (id_ref INT AUTO_INCREMENT NOT NULL, ref_hebergement INT DEFAULT NULL, pourcentage INT NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, INDEX fk_refH (ref_hebergement), PRIMARY KEY(id_ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idR INT AUTO_INCREMENT NOT NULL, objet VARCHAR(100) NOT NULL, description VARCHAR(200) NOT NULL, etat VARCHAR(50) NOT NULL, idClient INT DEFAULT NULL, INDEX idClient (idClient), PRIMARY KEY(idR)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_voyage INT DEFAULT NULL, id_activite INT DEFAULT NULL, id_hebergement INT DEFAULT NULL, id_vol INT DEFAULT NULL, id_client INT DEFAULT NULL, date_reservation DATE NOT NULL, nbr_place INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, etat VARCHAR(30) NOT NULL, type VARCHAR(30) DEFAULT NULL, INDEX id_vol (id_vol), INDEX fk_client (id_client), INDEX fk_heb (id_hebergement), INDEX fk_voyage (id_voyage), INDEX fk_act (id_activite), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, securityQ VARCHAR(255) DEFAULT NULL, answer VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, numtel INT DEFAULT NULL, nomAgence VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, etat VARCHAR(50) DEFAULT \'1\' NOT NULL, solde DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol (id_vol INT AUTO_INCREMENT NOT NULL, date_depart DATETIME NOT NULL, date_arrivee DATETIME NOT NULL, ville_depart VARCHAR(60) NOT NULL, ville_arrivee VARCHAR(50) NOT NULL, nbr_placedispo INT NOT NULL, id_avion INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX id_avion (id_avion), PRIMARY KEY(id_vol)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyageorganise (idVoy INT AUTO_INCREMENT NOT NULL, villeDepart VARCHAR(30) NOT NULL, villeDest VARCHAR(30) NOT NULL, dateDepart VARCHAR(20) NOT NULL, dateArrive VARCHAR(20) NOT NULL, nbrPlace INT NOT NULL, idCat INT NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(1000) NOT NULL, INDEX idCat (idCat), PRIMARY KEY(idVoy)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avion ADD CONSTRAINT FK_234D9D3842F62F44 FOREIGN KEY (id_agence) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF02ABD43F2 FOREIGN KEY (Id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF077C30A39 FOREIGN KEY (RefActivite) REFERENCES activite (RefAct)');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CED0B8043 FOREIGN KEY (id_categ) REFERENCES category (id_categ)');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CB05122F8 FOREIGN KEY (offreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E5ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE promostion ADD CONSTRAINT FK_84D8424CB25B2510 FOREIGN KEY (ref_hebergement) REFERENCES hebergement (referance)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A455ACCF FOREIGN KEY (idClient) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519AA3CB8 FOREIGN KEY (id_voyage) REFERENCES voyageorganise (idVoy)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E8AEB980 FOREIGN KEY (id_activite) REFERENCES activite (RefAct)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (referance)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495597F87FB1 FOREIGN KEY (id_vol) REFERENCES vol (id_vol)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E173B1B8 FOREIGN KEY (id_client) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF077C30A39');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E8AEB980');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CED0B8043');
        $this->addSql('ALTER TABLE promostion DROP FOREIGN KEY FK_84D8424CB25B2510');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555040106B');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E5ADA84A2');
        $this->addSql('ALTER TABLE avion DROP FOREIGN KEY FK_234D9D3842F62F44');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF02ABD43F2');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CB05122F8');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A455ACCF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E173B1B8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495597F87FB1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519AA3CB8');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE avion');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorievoy');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE hebergement');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE promostion');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vol');
        $this->addSql('DROP TABLE voyageorganise');
    }
}
