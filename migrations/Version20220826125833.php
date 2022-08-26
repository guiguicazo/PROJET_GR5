<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826125833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date ADD COLUMN motif VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, campus_id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(12) NOT NULL, mail VARCHAR(255) NOT NULL, administrateur BOOLEAN NOT NULL, actif BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at) SELECT id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__date AS SELECT id, etat_sortie_id, organisateur_id, campus_id, lieu_id, id_sortie, nom, date_heure_debut, duree, date_limite_inscritpion, nb_inscritpions_max, infos_sortie, etat FROM date');
        $this->addSql('DROP TABLE date');
        $this->addSql('CREATE TABLE date (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etat_sortie_id INTEGER DEFAULT NULL, organisateur_id INTEGER DEFAULT NULL, campus_id INTEGER NOT NULL, lieu_id INTEGER NOT NULL, id_sortie INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_debut DATETIME NOT NULL, duree INTEGER NOT NULL, date_limite_inscritpion DATETIME NOT NULL, nb_inscritpions_max INTEGER NOT NULL, infos_sortie VARCHAR(255) NOT NULL, etat INTEGER NOT NULL, CONSTRAINT FK_AA9E377A3CE09FBF FOREIGN KEY (etat_sortie_id) REFERENCES etat (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AA9E377AD936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AA9E377AAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AA9E377A6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO date (id, etat_sortie_id, organisateur_id, campus_id, lieu_id, id_sortie, nom, date_heure_debut, duree, date_limite_inscritpion, nb_inscritpions_max, infos_sortie, etat) SELECT id, etat_sortie_id, organisateur_id, campus_id, lieu_id, id_sortie, nom, date_heure_debut, duree, date_limite_inscritpion, nb_inscritpions_max, infos_sortie, etat FROM __temp__date');
        $this->addSql('DROP TABLE __temp__date');
        $this->addSql('CREATE INDEX IDX_AA9E377A3CE09FBF ON date (etat_sortie_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377AD936B2FA ON date (organisateur_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377AAF5D55E1 ON date (campus_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377A6AB213CC ON date (lieu_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, campus_id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(12) NOT NULL, mail VARCHAR(255) NOT NULL, administrateur BOOLEAN NOT NULL, actif BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at) SELECT id, campus_id, username, roles, password, nom, prenom, telephone, mail, administrateur, actif, image, updated_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)');
    }
}
