<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823123704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date_user (date_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AD4D5FBDB897366B (date_id), INDEX IDX_AD4D5FBDA76ED395 (user_id), PRIMARY KEY(date_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDB897366B FOREIGN KEY (date_id) REFERENCES date (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date_user ADD CONSTRAINT FK_AD4D5FBDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE date ADD etat_sortie_id INT DEFAULT NULL, ADD lieu_id INT DEFAULT NULL, ADD campus_id INT DEFAULT NULL, ADD organisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A3CE09FBF FOREIGN KEY (etat_sortie_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377A6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AAF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE date ADD CONSTRAINT FK_AA9E377AD936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AA9E377A3CE09FBF ON date (etat_sortie_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377A6AB213CC ON date (lieu_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377AAF5D55E1 ON date (campus_id)');
        $this->addSql('CREATE INDEX IDX_AA9E377AD936B2FA ON date (organisateur_id)');
        $this->addSql('ALTER TABLE lieu ADD ville_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_2F577D59A73F0036 ON lieu (ville_id)');
        $this->addSql('ALTER TABLE user ADD campus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDB897366B');
        $this->addSql('ALTER TABLE date_user DROP FOREIGN KEY FK_AD4D5FBDA76ED395');
        $this->addSql('DROP TABLE date_user');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A3CE09FBF');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377A6AB213CC');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AAF5D55E1');
        $this->addSql('ALTER TABLE date DROP FOREIGN KEY FK_AA9E377AD936B2FA');
        $this->addSql('DROP INDEX IDX_AA9E377A3CE09FBF ON date');
        $this->addSql('DROP INDEX IDX_AA9E377A6AB213CC ON date');
        $this->addSql('DROP INDEX IDX_AA9E377AAF5D55E1 ON date');
        $this->addSql('DROP INDEX IDX_AA9E377AD936B2FA ON date');
        $this->addSql('ALTER TABLE date DROP etat_sortie_id, DROP lieu_id, DROP campus_id, DROP organisateur_id');
        $this->addSql('ALTER TABLE lieu DROP FOREIGN KEY FK_2F577D59A73F0036');
        $this->addSql('DROP INDEX IDX_2F577D59A73F0036 ON lieu');
        $this->addSql('ALTER TABLE lieu DROP ville_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('DROP INDEX IDX_8D93D649AF5D55E1 ON user');
        $this->addSql('ALTER TABLE user DROP campus_id');
    }
}
