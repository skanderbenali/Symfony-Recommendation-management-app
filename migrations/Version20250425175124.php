<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425175124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (idcommande INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, prenom VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(200) NOT NULL, telephone INT NOT NULL, email VARCHAR(255) NOT NULL, prix_totale DOUBLE PRECISION DEFAULT NULL, dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX id_user (id_user), PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id_evenement INT AUTO_INCREMENT NOT NULL, nom_event VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id_evenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_conseil (id INT AUTO_INCREMENT NOT NULL, id_conseil INT DEFAULT NULL, id_user INT DEFAULT NULL, INDEX id_user (id_user), INDEX conseil_fav (id_conseil), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id_offre INT AUTO_INCREMENT NOT NULL, id_evenement_offre INT DEFAULT NULL, montant_remise DOUBLE PRECISION NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description VARCHAR(255) NOT NULL, rating INT NOT NULL, id_produit_offre INT NOT NULL, INDEX id_evenement (id_evenement_offre), INDEX offre_produit (id_produit_offre), PRIMARY KEY(id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, id_produit INT DEFAULT NULL, id_user INT DEFAULT NULL, id_commande INT DEFAULT NULL, quantite INT DEFAULT NULL, prix_u DOUBLE PRECISION DEFAULT NULL, INDEX id_user (id_user), INDEX id_produit (id_produit), PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prod (id_produit INT AUTO_INCREMENT NOT NULL, id_categorie INT DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, prix INT NOT NULL, quantite INT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX produit_categorie (id_categorie), PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typeconseil (idTypeC INT AUTO_INCREMENT NOT NULL, nomTypeC VARCHAR(255) NOT NULL, PRIMARY KEY(idTypeC)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, tel INT DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, date_naiss DATE DEFAULT NULL, token LONGTEXT DEFAULT NULL, photo LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_actif TINYINT(1) NOT NULL, role VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EFE1A2AFF1 FOREIGN KEY (id_conseil) REFERENCES conseil (id_conseil)');
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EF6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FD572E91F FOREIGN KEY (id_evenement_offre) REFERENCES evenement (id_evenement)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prod ADD CONSTRAINT FK_B5B41197C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('DROP TABLE produits');
        $this->addSql('ALTER TABLE conseil CHANGE video video VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE conseil RENAME INDEX id_typec TO IDX_3F3F0681916CF8AC');
        $this->addSql('ALTER TABLE conseil RENAME INDEX id_produit TO IDX_3F3F0681F7384557');
        $this->addSql('ALTER TABLE produit ADD id_categorie INT DEFAULT NULL, ADD prix INT NOT NULL, ADD quantite INT NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD id_offre INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_29A5EC27C9486A13 ON produit (id_categorie)');
        $this->addSql('ALTER TABLE review ADD titile VARCHAR(255) NOT NULL, DROP title, CHANGE comments comments VARCHAR(255) NOT NULL, CHANGE value value INT NOT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE review RENAME INDEX id_conseil TO IDX_794381C6E1A2AFF1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681916CF8AC');
        $this->addSql('CREATE TABLE produits (id_produit INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EFE1A2AFF1');
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EF6B3CA4B');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FD572E91F');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7384557');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26B3CA4B');
        $this->addSql('ALTER TABLE prod DROP FOREIGN KEY FK_B5B41197C9486A13');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE favorite_conseil');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE prod');
        $this->addSql('DROP TABLE typeconseil');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681F7384557');
        $this->addSql('ALTER TABLE conseil CHANGE video video VARCHAR(255) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE conseil RENAME INDEX idx_3f3f0681f7384557 TO id_produit');
        $this->addSql('ALTER TABLE conseil RENAME INDEX idx_3f3f0681916cf8ac TO id_typeC');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('DROP INDEX IDX_29A5EC27C9486A13 ON produit');
        $this->addSql('ALTER TABLE produit DROP id_categorie, DROP prix, DROP quantite, DROP description, DROP image, DROP id_offre');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E1A2AFF1');
        $this->addSql('ALTER TABLE review ADD title VARCHAR(255) DEFAULT NULL, DROP titile, CHANGE comments comments TEXT DEFAULT NULL, CHANGE value value INT DEFAULT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE review RENAME INDEX idx_794381c6e1a2aff1 TO id_conseil');
    }
}
