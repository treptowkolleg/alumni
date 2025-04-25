<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425041619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE direct_message (id VARCHAR(12) NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, content LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, is_pinned TINYINT(1) NOT NULL, title VARCHAR(100) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1416AF93F624B39D (sender_id), INDEX IDX_1416AF93E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93F624B39D');
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93E92F8F78');
        $this->addSql('DROP TABLE direct_message');
    }
}
