<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412210740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subject_post (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, text LONGTEXT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C840E856727ACA70 (parent_id), INDEX IDX_C840E8567E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E856727ACA70 FOREIGN KEY (parent_id) REFERENCES subject_post (id)');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E8567E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E856727ACA70');
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E8567E3C61F9');
        $this->addSql('DROP TABLE subject_post');
    }
}
