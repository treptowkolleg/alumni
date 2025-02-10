<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210101838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pinboard_entry (id INT AUTO_INCREMENT NOT NULL, user_profile_id INT DEFAULT NULL, writer_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_91CA6E956B9DD454 (user_profile_id), INDEX IDX_91CA6E951BC7E6B6 (writer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pinboard_entry ADD CONSTRAINT FK_91CA6E956B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE pinboard_entry ADD CONSTRAINT FK_91CA6E951BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE user ADD has_pinnboard TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pinboard_entry DROP FOREIGN KEY FK_91CA6E956B9DD454');
        $this->addSql('ALTER TABLE pinboard_entry DROP FOREIGN KEY FK_91CA6E951BC7E6B6');
        $this->addSql('DROP TABLE pinboard_entry');
        $this->addSql('ALTER TABLE user DROP has_pinnboard');
    }
}
