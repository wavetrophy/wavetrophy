<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516190700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED964D218E');
        $this->addSql('DROP INDEX fk_hotel_location1_idx ON hotel');
        $this->addSql('ALTER TABLE hotel ADD check_in DATETIME NOT NULL, ADD thumbnail VARCHAR(255) DEFAULT NULL, ADD thumbnail_url VARCHAR(255) DEFAULT NULL, ADD lon VARCHAR(20) NOT NULL, ADD lat VARCHAR(20) NOT NULL, DROP comment, CHANGE location_id wave_id INT DEFAULT NULL, CHANGE last_check_in check_out DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED99461E358 FOREIGN KEY (wave_id) REFERENCES wave (id)');
        $this->addSql('CREATE INDEX IDX_3535ED99461E358 ON hotel (wave_id)');
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants, not support\'');
        $this->addSql('ALTER TABLE lodging ADD created_by INT DEFAULT NULL, ADD updated_by INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182ADE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D35182ADE12AB56 ON lodging (created_by)');
        $this->addSql('CREATE INDEX IDX_8D35182A16FE72E1 ON lodging (updated_by)');
        $this->addSql('ALTER TABLE media ADD created_by INT DEFAULT NULL, ADD updated_by INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CDE12AB56 ON media (created_by)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C16FE72E1 ON media (updated_by)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED99461E358');
        $this->addSql('DROP INDEX IDX_3535ED99461E358 ON hotel');
        $this->addSql('ALTER TABLE hotel ADD comment LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP check_in, DROP thumbnail, DROP thumbnail_url, DROP lon, DROP lat, CHANGE wave_id location_id INT DEFAULT NULL, CHANGE check_out last_check_in DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED964D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX fk_hotel_location1_idx ON hotel (location_id)');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182ADE12AB56');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A16FE72E1');
        $this->addSql('DROP INDEX IDX_8D35182ADE12AB56 ON lodging');
        $this->addSql('DROP INDEX IDX_8D35182A16FE72E1 ON lodging');
        $this->addSql('ALTER TABLE lodging DROP created_by, DROP updated_by, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CDE12AB56');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C16FE72E1');
        $this->addSql('DROP INDEX IDX_6A2CA10CDE12AB56 ON media');
        $this->addSql('DROP INDEX IDX_6A2CA10C16FE72E1 ON media');
        $this->addSql('ALTER TABLE media DROP created_by, DROP updated_by, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants,
                     *     not support\'');
    }
}
