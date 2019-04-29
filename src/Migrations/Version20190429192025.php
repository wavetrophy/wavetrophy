<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190429192025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE team_participation CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE event CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE hotel CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE `group` CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE question ADD resolved TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\', CHANGE title title VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants,
             *     not support\', CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE location CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE user_phonenumber CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE user_email CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE answer CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE event CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE `group` CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE hotel CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE location CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE question DROP resolved, CHANGE title title VARCHAR(1000) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE team CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE team_participation CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user_email CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE user_phonenumber CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants,
                     *     not support\', CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
    }
}
