<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190306235802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team_has_participations (team_participation_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_1916966DD503952 (team_participation_id), INDEX IDX_1916966296CD8AE (team_id), PRIMARY KEY(team_participation_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_has_participations ADD CONSTRAINT FK_1916966DD503952 FOREIGN KEY (team_participation_id) REFERENCES team_participation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_has_participations ADD CONSTRAINT FK_1916966296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE team_participation_team');
        $this->addSql('ALTER TABLE team CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
        $this->addSql('ALTER TABLE team_participation CHANGE departure departure DATETIME NOT NULL, CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
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
        $this->addSql('ALTER TABLE question CHANGE created_by created_by VARCHAR(80) NOT NULL COMMENT \'The id of the
             *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COMMENT \'The user who
             *     updated the record\'');
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

        $this->addSql('CREATE TABLE team_participation_team (team_participation_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_7A05DA07DD503952 (team_participation_id), INDEX IDX_7A05DA07296CD8AE (team_id), PRIMARY KEY(team_participation_id, team_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE team_participation_team ADD CONSTRAINT FK_7A05DA07296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_participation_team ADD CONSTRAINT FK_7A05DA07DD503952 FOREIGN KEY (team_participation_id) REFERENCES team_participation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE team_has_participations');
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
        $this->addSql('ALTER TABLE question CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE team CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
        $this->addSql('ALTER TABLE team_participation CHANGE departure departure VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
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
        $this->addSql('ALTER TABLE wave CHANGE start start DATETIME NOT NULL COMMENT \'The start for the participants, not support\', CHANGE created_by created_by VARCHAR(80) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The id of the
                     *     user\', CHANGE updated_by updated_by VARCHAR(80) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'The user who
                     *     updated the record\'');
    }
}
