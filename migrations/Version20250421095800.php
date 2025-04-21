<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421095800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_answer (id INT AUTO_INCREMENT NOT NULL, survey_id INT DEFAULT NULL, user_id INT DEFAULT NULL, answer TINYINT(1) NOT NULL, INDEX IDX_F2D38249B3FE509D (survey_id), INDEX IDX_F2D38249A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D38249B3FE509D');
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D38249A76ED395');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE survey_answer');
    }
}
