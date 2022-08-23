<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823125008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, id_campus INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date (id INT AUTO_INCREMENT NOT NULL, etat_sortie_id INT DEFAULT NULL, lieu_id INT DEFAULT NULL, campus_id INT DEFAULT NULL, organisateur_id INT DEFAULT NULL, id_sortie INT NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_debut DATETIME NOT NULL, duree INT NOT NULL, date_limite_inscritpion DATETIME NOT NULL, nb_inscritpions_max INT NOT NULL, infos_sortie VARCHAR(255) NOT NULL, etat INT NOT NULL, INDEX IDX_AA9E377A3CE09FBF (etat_sortie_id), INDEX IDX_AA9E377A6AB213CC (lieu_id), INDEX IDX_AA9E377AAF5D55E1 (campus_id), INDEX IDX_AA9E377AD936B2FA (organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_user (date_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AD4D5FBDB897366B (date_id), INDEX IDX_AD4D5FBDA76ED395 (user_id), PRIMARY KEY(date_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, id_lieu INT NOT NULL, nom VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_2F577D59A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, campus_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(12) NOT NULL, mail VARCHAR(255) NOT NULL, administrateur TINYINT(1) NOT NULL, actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649AF5D55E1 (campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, id_ville INT NOT NULL, nom VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A3CE09FBF FOREIGN KEY (etat_sortie_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AD936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDB897366B FOREIGN KEY (date_id) REFERENCES date (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A3CE09FBF');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A6AB213CC');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AAF5D55E1');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AD936B2FA');
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDB897366B');
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDA76ED395');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59A73F0036');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE date');
        $this->addSql('DROP TABLE date_user');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
