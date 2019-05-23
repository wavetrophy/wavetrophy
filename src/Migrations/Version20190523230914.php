<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190523230914 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_activity (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_EA98E08A71F7E88B (event_id), INDEX IDX_EA98E08ADE12AB56 (created_by), INDEX IDX_EA98E08A16FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_activity ADD CONSTRAINT FK_EA98E08A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_activity ADD CONSTRAINT FK_EA98E08ADE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_activity ADD CONSTRAINT FK_EA98E08A16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('DROP TABLE location');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, wave_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci, lat VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci, lon VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci, thumbnail VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_5E9E89CB9461E358 (wave_id), INDEX IDX_5E9E89CBDE12AB56 (created_by), INDEX IDX_5E9E89CB16FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB9461E358 FOREIGN KEY (wave_id) REFERENCES wave (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE event_activity');
    }
}
