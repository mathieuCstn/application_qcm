<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925124805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qcm_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qcmsession_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qcmsession_choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE choice (id INT NOT NULL, question_id INT NOT NULL, choice_content VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, feedback VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1AB5A921E27F6BF ON choice (question_id)');
        $this->addSql('CREATE TABLE qcm (id INT NOT NULL, questions_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D7A1FEF4BCB134CE ON qcm (questions_id)');
        $this->addSql('COMMENT ON COLUMN qcm.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE qcmsession (id INT NOT NULL, participant_id INT NOT NULL, qcm_id INT NOT NULL, realized_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_992984719D1C3019 ON qcmsession (participant_id)');
        $this->addSql('CREATE INDEX IDX_99298471FF6241A6 ON qcmsession (qcm_id)');
        $this->addSql('COMMENT ON COLUMN qcmsession.realized_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE qcmsession_choice (id INT NOT NULL, choice_id INT NOT NULL, qcm_session_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB3FD31B998666D1 ON qcmsession_choice (choice_id)');
        $this->addSql('CREATE INDEX IDX_FB3FD31BCAEC5DC9 ON qcmsession_choice (qcm_session_id)');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE choice ADD CONSTRAINT FK_C1AB5A921E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT FK_D7A1FEF4BCB134CE FOREIGN KEY (questions_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcmsession ADD CONSTRAINT FK_992984719D1C3019 FOREIGN KEY (participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcmsession ADD CONSTRAINT FK_99298471FF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcmsession_choice ADD CONSTRAINT FK_FB3FD31B998666D1 FOREIGN KEY (choice_id) REFERENCES choice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcmsession_choice ADD CONSTRAINT FK_FB3FD31BCAEC5DC9 FOREIGN KEY (qcm_session_id) REFERENCES qcmsession (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE choice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qcm_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qcmsession_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qcmsession_choice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE choice DROP CONSTRAINT FK_C1AB5A921E27F6BF');
        $this->addSql('ALTER TABLE qcm DROP CONSTRAINT FK_D7A1FEF4BCB134CE');
        $this->addSql('ALTER TABLE qcmsession DROP CONSTRAINT FK_992984719D1C3019');
        $this->addSql('ALTER TABLE qcmsession DROP CONSTRAINT FK_99298471FF6241A6');
        $this->addSql('ALTER TABLE qcmsession_choice DROP CONSTRAINT FK_FB3FD31B998666D1');
        $this->addSql('ALTER TABLE qcmsession_choice DROP CONSTRAINT FK_FB3FD31BCAEC5DC9');
        $this->addSql('DROP TABLE choice');
        $this->addSql('DROP TABLE qcm');
        $this->addSql('DROP TABLE qcmsession');
        $this->addSql('DROP TABLE qcmsession_choice');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE "user"');
    }
}
