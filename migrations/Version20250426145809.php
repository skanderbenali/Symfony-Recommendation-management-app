<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426145809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE conseil CHANGE dateCreation dateCreation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EFE1A2AFF1 FOREIGN KEY (id_conseil) REFERENCES conseil (id_conseil)');
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EF6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prod ADD CONSTRAINT FK_B5B41197C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE produit RENAME INDEX fk_produit_categorie TO IDX_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE review CHANGE title title VARCHAR(255) NOT NULL, CHANGE comments comments VARCHAR(255) NOT NULL, CHANGE value value INT NOT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE review RENAME INDEX id_conseil TO IDX_794381C6E1A2AFF1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id_evenement INT AUTO_INCREMENT NOT NULL, nom_event VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_evenement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (idcommande INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, prenom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone INT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prix_totale DOUBLE PRECISION DEFAULT NULL, dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX id_user (id_user), PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681916CF8AC');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681F7384557');
        $this->addSql('ALTER TABLE conseil CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7384557');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26B3CA4B');
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EFE1A2AFF1');
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EF6B3CA4B');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E1A2AFF1');
        $this->addSql('ALTER TABLE review CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE comments comments TEXT DEFAULT NULL, CHANGE value value INT DEFAULT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE review RENAME INDEX idx_794381c6e1a2aff1 TO id_conseil');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_29a5ec27c9486a13 TO FK_produit_categorie');
        $this->addSql('ALTER TABLE prod DROP FOREIGN KEY FK_B5B41197C9486A13');
    }
}
