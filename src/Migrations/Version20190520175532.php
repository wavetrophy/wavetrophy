<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520175532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_has_participations DROP FOREIGN KEY FK_1916966DD503952');
        $this->addSql('CREATE TABLE event_participation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, arrival DATETIME NOT NULL, departure DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_8F0C52E371F7E88B (event_id), INDEX IDX_8F0C52E3DE12AB56 (created_by), INDEX IDX_8F0C52E316FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_participation ADD CONSTRAINT FK_8F0C52E371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_participation ADD CONSTRAINT FK_8F0C52E3DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_participation ADD CONSTRAINT FK_8F0C52E316FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('DROP TABLE team_participation');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E');
        $this->addSql('DROP INDEX fk_event_location1_idx ON event');
        $this->addSql('ALTER TABLE event ADD thumbnail VARCHAR(255) DEFAULT NULL, ADD thumbnail_url VARCHAR(255) DEFAULT NULL, ADD lon VARCHAR(20) NOT NULL, ADD lat VARCHAR(20) NOT NULL, CHANGE location_id wave_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA79461E358 FOREIGN KEY (wave_id) REFERENCES wave (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA79461E358 ON event (wave_id)');
        $this->addSql('DROP INDEX IDX_1916966DD503952 ON team_has_participations');
        $this->addSql('ALTER TABLE team_has_participations DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE team_has_participations CHANGE team_participation_id event_participation_id INT NOT NULL');
        $this->addSql('ALTER TABLE team_has_participations ADD CONSTRAINT FK_19169664CDED8BB FOREIGN KEY (event_participation_id) REFERENCES event_participation (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_19169664CDED8BB ON team_has_participations (event_participation_id)');
        $this->addSql('ALTER TABLE team_has_participations ADD PRIMARY KEY (event_participation_id, team_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_has_participations DROP FOREIGN KEY FK_19169664CDED8BB');
        $this->addSql('CREATE TABLE team_participation (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, arrival DATETIME NOT NULL, departure DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_DE37C7DE16FE72E1 (updated_by), INDEX IDX_DE37C7DEDE12AB56 (created_by), INDEX fk_team_participation_location1_idx (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DE16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DE64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DEDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE event_participation');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA79461E358');
        $this->addSql('DROP INDEX IDX_3BAE0AA79461E358 ON event');
        $this->addSql('ALTER TABLE event DROP thumbnail, DROP thumbnail_url, DROP lon, DROP lat, CHANGE wave_id location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX fk_event_location1_idx ON event (location_id)');
        $this->addSql('DROP INDEX IDX_19169664CDED8BB ON team_has_participations');
        $this->addSql('ALTER TABLE team_has_participations DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE team_has_participations CHANGE event_participation_id team_participation_id INT NOT NULL');
        $this->addSql('ALTER TABLE team_has_participations ADD CONSTRAINT FK_1916966DD503952 FOREIGN KEY (team_participation_id) REFERENCES team_participation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1916966DD503952 ON team_has_participations (team_participation_id)');
        $this->addSql('ALTER TABLE team_has_participations ADD PRIMARY KEY (team_participation_id, team_id)');
    }
}
