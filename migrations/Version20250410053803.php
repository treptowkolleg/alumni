<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410053803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hobby ADD category_id INT DEFAULT NULL, ADD label VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE hobby ADD CONSTRAINT FK_3964F33712469DE2 FOREIGN KEY (category_id) REFERENCES hobby_category (id)');
        $this->addSql('CREATE INDEX IDX_3964F33712469DE2 ON hobby (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hobby DROP FOREIGN KEY FK_3964F33712469DE2');
        $this->addSql('DROP TABLE hobby_category');
        $this->addSql('DROP INDEX IDX_3964F33712469DE2 ON hobby');
        $this->addSql('ALTER TABLE hobby DROP category_id, DROP label');
    }
}
