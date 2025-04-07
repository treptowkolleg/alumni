<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407173334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_level (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_offer ADD job_type_id INT DEFAULT NULL, ADD salary_level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FA5FA33B08 FOREIGN KEY (job_type_id) REFERENCES job_type (id)');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FACDF4C1A6 FOREIGN KEY (salary_level_id) REFERENCES salary_level (id)');
        $this->addSql('CREATE INDEX IDX_85E098FA5FA33B08 ON person_offer (job_type_id)');
        $this->addSql('CREATE INDEX IDX_85E098FACDF4C1A6 ON person_offer (salary_level_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FA5FA33B08');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FACDF4C1A6');
        $this->addSql('DROP TABLE job_type');
        $this->addSql('DROP TABLE salary_level');
        $this->addSql('DROP INDEX IDX_85E098FA5FA33B08 ON person_offer');
        $this->addSql('DROP INDEX IDX_85E098FACDF4C1A6 ON person_offer');
        $this->addSql('ALTER TABLE person_offer DROP job_type_id, DROP salary_level_id');
    }
}
