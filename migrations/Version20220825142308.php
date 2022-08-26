<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825142308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date CHANGE lieu_id lieu_id INT NOT NULL, CHANGE campus_id campus_id INT NOT NULL');
        $this->addSql('ALTER TABLE lieu DROP id_lieu, CHANGE ville_id ville_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD image VARCHAR(255) DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE campus_id campus_id INT NOT NULL');
        $this->addSql('ALTER TABLE ville DROP id_ville');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date CHANGE campus_id campus_id INT DEFAULT NULL, CHANGE lieu_id lieu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lieu ADD id_lieu INT NOT NULL, CHANGE ville_id ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP image, DROP updated_at, CHANGE campus_id campus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ville ADD id_ville INT NOT NULL');
    }
}
