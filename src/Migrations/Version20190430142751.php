<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430142751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FDE12AB56 ON team (created_by)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F16FE72E1 ON team (updated_by)');
        $this->addSql('ALTER TABLE team_participation CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DEDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team_participation ADD CONSTRAINT FK_DE37C7DE16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DE37C7DEDE12AB56 ON team_participation (created_by)');
        $this->addSql('CREATE INDEX IDX_DE37C7DE16FE72E1 ON team_participation (updated_by)');
        $this->addSql('ALTER TABLE event CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA716FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7DE12AB56 ON event (created_by)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA716FE72E1 ON event (updated_by)');
        $this->addSql('ALTER TABLE hotel CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED916FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3535ED9DE12AB56 ON hotel (created_by)');
        $this->addSql('CREATE INDEX IDX_3535ED916FE72E1 ON hotel (updated_by)');
        $this->addSql('ALTER TABLE `group` CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C516FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C5DE12AB56 ON `group` (created_by)');
        $this->addSql('CREATE INDEX IDX_6DC044C516FE72E1 ON `group` (updated_by)');
        $this->addSql('ALTER TABLE question CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL, CHANGE resolved resolved TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EDE12AB56 ON question (created_by)');
        $this->addSql('CREATE INDEX IDX_B6F7494E16FE72E1 ON question (updated_by)');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DE12AB56 ON user (created_by)');
        $this->addSql('CREATE INDEX IDX_8D93D64916FE72E1 ON user (updated_by)');
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants,
             *     not support\', CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wave ADD CONSTRAINT FK_DA04AD89DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wave ADD CONSTRAINT FK_DA04AD8916FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DA04AD89DE12AB56 ON wave (created_by)');
        $this->addSql('CREATE INDEX IDX_DA04AD8916FE72E1 ON wave (updated_by)');
        $this->addSql('ALTER TABLE location CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CBDE12AB56 ON location (created_by)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB16FE72E1 ON location (updated_by)');
        $this->addSql('ALTER TABLE user_phonenumber CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_phonenumber ADD CONSTRAINT FK_46E94F47DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_phonenumber ADD CONSTRAINT FK_46E94F4716FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_46E94F47DE12AB56 ON user_phonenumber (created_by)');
        $this->addSql('CREATE INDEX IDX_46E94F4716FE72E1 ON user_phonenumber (updated_by)');
        $this->addSql('ALTER TABLE user_email CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_email ADD CONSTRAINT FK_550872CDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_email ADD CONSTRAINT FK_550872C16FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_550872CDE12AB56 ON user_email (created_by)');
        $this->addSql('CREATE INDEX IDX_550872C16FE72E1 ON user_email (updated_by)');
        $this->addSql('ALTER TABLE answer CHANGE approved approved TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A2516FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25DE12AB56 ON answer (created_by)');
        $this->addSql('CREATE INDEX IDX_DADD4A2516FE72E1 ON answer (updated_by)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25DE12AB56');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A2516FE72E1');
        $this->addSql('DROP INDEX IDX_DADD4A25DE12AB56 ON answer');
        $this->addSql('DROP INDEX IDX_DADD4A2516FE72E1 ON answer');
        $this->addSql('ALTER TABLE answer CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\', CHANGE approved approved TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7DE12AB56');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA716FE72E1');
        $this->addSql('DROP INDEX IDX_3BAE0AA7DE12AB56 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA716FE72E1 ON event');
        $this->addSql('ALTER TABLE event CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5DE12AB56');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C516FE72E1');
        $this->addSql('DROP INDEX IDX_6DC044C5DE12AB56 ON `group`');
        $this->addSql('DROP INDEX IDX_6DC044C516FE72E1 ON `group`');
        $this->addSql('ALTER TABLE `group` CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED9DE12AB56');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED916FE72E1');
        $this->addSql('DROP INDEX IDX_3535ED9DE12AB56 ON hotel');
        $this->addSql('DROP INDEX IDX_3535ED916FE72E1 ON hotel');
        $this->addSql('ALTER TABLE hotel CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBDE12AB56');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB16FE72E1');
        $this->addSql('DROP INDEX IDX_5E9E89CBDE12AB56 ON location');
        $this->addSql('DROP INDEX IDX_5E9E89CB16FE72E1 ON location');
        $this->addSql('ALTER TABLE location CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EDE12AB56');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E16FE72E1');
        $this->addSql('DROP INDEX IDX_B6F7494EDE12AB56 ON question');
        $this->addSql('DROP INDEX IDX_B6F7494E16FE72E1 ON question');
        $this->addSql('ALTER TABLE question CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\', CHANGE resolved resolved TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FDE12AB56');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F16FE72E1');
        $this->addSql('DROP INDEX IDX_C4E0A61FDE12AB56 ON team');
        $this->addSql('DROP INDEX IDX_C4E0A61F16FE72E1 ON team');
        $this->addSql('ALTER TABLE team CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE team_participation DROP FOREIGN KEY FK_DE37C7DEDE12AB56');
        $this->addSql('ALTER TABLE team_participation DROP FOREIGN KEY FK_DE37C7DE16FE72E1');
        $this->addSql('DROP INDEX IDX_DE37C7DEDE12AB56 ON team_participation');
        $this->addSql('DROP INDEX IDX_DE37C7DE16FE72E1 ON team_participation');
        $this->addSql('ALTER TABLE team_participation CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DE12AB56');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64916FE72E1');
        $this->addSql('DROP INDEX IDX_8D93D649DE12AB56 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64916FE72E1 ON user');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user_email DROP FOREIGN KEY FK_550872CDE12AB56');
        $this->addSql('ALTER TABLE user_email DROP FOREIGN KEY FK_550872C16FE72E1');
        $this->addSql('DROP INDEX IDX_550872CDE12AB56 ON user_email');
        $this->addSql('DROP INDEX IDX_550872C16FE72E1 ON user_email');
        $this->addSql('ALTER TABLE user_email CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user_phonenumber DROP FOREIGN KEY FK_46E94F47DE12AB56');
        $this->addSql('ALTER TABLE user_phonenumber DROP FOREIGN KEY FK_46E94F4716FE72E1');
        $this->addSql('DROP INDEX IDX_46E94F47DE12AB56 ON user_phonenumber');
        $this->addSql('DROP INDEX IDX_46E94F4716FE72E1 ON user_phonenumber');
        $this->addSql('ALTER TABLE user_phonenumber CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE wave DROP FOREIGN KEY FK_DA04AD89DE12AB56');
        $this->addSql('ALTER TABLE wave DROP FOREIGN KEY FK_DA04AD8916FE72E1');
        $this->addSql('DROP INDEX IDX_DA04AD89DE12AB56 ON wave');
        $this->addSql('DROP INDEX IDX_DA04AD8916FE72E1 ON wave');
        $this->addSql('ALTER TABLE wave CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\', CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants,
                     *     not support\'');
    }
}
