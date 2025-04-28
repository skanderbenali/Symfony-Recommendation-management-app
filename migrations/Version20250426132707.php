<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426132707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EFE1A2AFF1 FOREIGN KEY (id_conseil) REFERENCES conseil (id_conseil)');
        $this->addSql('ALTER TABLE favorite_conseil ADD CONSTRAINT FK_626BA8EF6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7384557 FOREIGN KEY (id_produit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prod ADD CONSTRAINT FK_B5B41197C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE produit ADD id_categorie INT DEFAULT NULL, ADD prix INT NOT NULL, ADD quantite INT NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD id_offre INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_29A5EC27C9486A13 ON produit (id_categorie)');
        $this->addSql('ALTER TABLE review ADD titile VARCHAR(255) NOT NULL, DROP title, CHANGE comments comments VARCHAR(255) NOT NULL, CHANGE value value INT NOT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE review RENAME INDEX id_conseil TO IDX_794381C6E1A2AFF1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EFE1A2AFF1');
        $this->addSql('ALTER TABLE favorite_conseil DROP FOREIGN KEY FK_626BA8EF6B3CA4B');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681916CF8AC');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681F7384557');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('DROP INDEX IDX_29A5EC27C9486A13 ON produit');
        $this->addSql('ALTER TABLE produit DROP id_categorie, DROP prix, DROP quantite, DROP description, DROP image, DROP id_offre');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7384557');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26B3CA4B');
        $this->addSql('ALTER TABLE prod DROP FOREIGN KEY FK_B5B41197C9486A13');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E1A2AFF1');
        $this->addSql('ALTER TABLE review ADD title VARCHAR(255) DEFAULT NULL, DROP titile, CHANGE comments comments TEXT DEFAULT NULL, CHANGE value value INT DEFAULT NULL, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE review RENAME INDEX idx_794381c6e1a2aff1 TO id_conseil');
    }
}
