<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190303113230 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, start_number INT NOT NULL COMMENT \'The start number of the team (like 56 or 2)\', created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_team_group1_idx (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_participation (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, arrival DATETIME NOT NULL, departure VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_team_participation_location1_idx (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_participation_team (team_participation_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_7A05DA07DD503952 (team_participation_id), INDEX IDX_7A05DA07296CD8AE (team_id), PRIMARY KEY(team_participation_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, description VARCHAR(250) DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_event_location1_idx (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, breakfast_included TINYINT(1) DEFAULT \'1\' NOT NULL, last_check_in DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_hotel_location1_idx (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, wave_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_group_wave1_idx (wave_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, user_id INT DEFAULT NULL, question VARCHAR(1000) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_question_user1_idx (user_id), INDEX fk_question_group1_idx (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', first_name VARCHAR(80) NOT NULL, last_name VARCHAR(80) NOT NULL, has_received_welcome_email TINYINT(1) NOT NULL COMMENT \'Indicates if the user already received his welcome email\', created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), INDEX fk_user_team1_idx (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wave (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(80) NOT NULL, start DATETIME NOT NULL COMMENT \'The start for the participants, not support\', end DATETIME NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, wave_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, lat VARCHAR(80) NOT NULL, lon VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX IDX_5E9E89CB9461E358 (wave_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_phonenumber (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, phonenumber VARCHAR(30) NOT NULL COMMENT \'The phone number of the user like +41765410128 (without spaces)\', country_code VARCHAR(5) NOT NULL COMMENT \'The Country code of the phone number (e.g. +41 or +1 or +502)\', is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_user_phonenumber_user1_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging (id INT AUTO_INCREMENT NOT NULL, hotel_id INT DEFAULT NULL, comment VARCHAR(1000) DEFAULT NULL COMMENT \'Some personal information for the user\', INDEX fk_lodging_hotel1_idx (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging_user (lodging_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_62E72FFA87335AF1 (lodging_id), INDEX IDX_62E72FFAA76ED395 (user_id), PRIMARY KEY(lodging_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_email (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, is_public TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_user_email_user1_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, answer VARCHAR(1000) NOT NULL, approved TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', deleted_at DATETIME DEFAULT NULL, INDEX fk_answer_question1_idx (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DE64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE team_participation_team ADD CONSTRAINT FK_7A05DA07DD503952 FOREIGN KEY (team_participation_id) REFERENCES team_participation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_participation_team ADD CONSTRAINT FK_7A05DA07296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED964D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C59461E358 FOREIGN KEY (wave_id) REFERENCES wave (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB9461E358 FOREIGN KEY (wave_id) REFERENCES wave (id)');
        $this->addSql('ALTER TABLE user_phonenumber ADD CONSTRAINT FK_46E94F47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE lodging_user ADD CONSTRAINT FK_62E72FFA87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_user ADD CONSTRAINT FK_62E72FFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_email ADD CONSTRAINT FK_550872CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_participation_team DROP FOREIGN KEY FK_7A05DA07296CD8AE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('ALTER TABLE team_participation_team DROP FOREIGN KEY FK_7A05DA07DD503952');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A3243BB18');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FFE54D947');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EFE54D947');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EA76ED395');
        $this->addSql('ALTER TABLE user_phonenumber DROP FOREIGN KEY FK_46E94F47A76ED395');
        $this->addSql('ALTER TABLE lodging_user DROP FOREIGN KEY FK_62E72FFAA76ED395');
        $this->addSql('ALTER TABLE user_email DROP FOREIGN KEY FK_550872CA76ED395');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C59461E358');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB9461E358');
        $this->addSql('ALTER TABLE team_participation DROP FOREIGN KEY FK_DE37C7DE64D218E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED964D218E');
        $this->addSql('ALTER TABLE lodging_user DROP FOREIGN KEY FK_62E72FFA87335AF1');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_participation');
        $this->addSql('DROP TABLE team_participation_team');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wave');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE user_phonenumber');
        $this->addSql('DROP TABLE lodging');
        $this->addSql('DROP TABLE lodging_user');
        $this->addSql('DROP TABLE user_email');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
