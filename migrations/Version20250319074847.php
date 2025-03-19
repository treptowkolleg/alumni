<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319074847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletter_queue (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, receiver_email VARCHAR(255) NOT NULL, send_date DATETIME NOT NULL, send TINYINT(1) NOT NULL, user_count INT NOT NULL, INDEX IDX_F59033975DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_queue ADD CONSTRAINT FK_F59033975DA0FB8 FOREIGN KEY (template_id) REFERENCES newsletter_template (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_queue DROP FOREIGN KEY FK_F59033975DA0FB8');
        $this->addSql('DROP TABLE newsletter_queue');
    }
}
