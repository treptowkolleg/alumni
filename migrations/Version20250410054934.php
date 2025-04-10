<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410054934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interest (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_6C3E1A67989D9B62 (slug), INDEX IDX_6C3E1A6712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_7A9F8C01989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE university (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, short_title VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, county VARCHAR(100) NOT NULL, city VARCHAR(255) NOT NULL, plz VARCHAR(5) NOT NULL, street VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A07A85EC989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE interest ADD CONSTRAINT FK_6C3E1A6712469DE2 FOREIGN KEY (category_id) REFERENCES interest_category (id)');
        $this->addSql('ALTER TABLE hobby ADD slug VARCHAR(128) NOT NULL, DROP title');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3964F337989D9B62 ON hobby (slug)');
        $this->addSql('ALTER TABLE hobby_category ADD slug VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FB4DC80989D9B62 ON hobby_category (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interest DROP FOREIGN KEY FK_6C3E1A6712469DE2');
        $this->addSql('DROP TABLE interest');
        $this->addSql('DROP TABLE interest_category');
        $this->addSql('DROP TABLE university');
        $this->addSql('DROP INDEX UNIQ_3964F337989D9B62 ON hobby');
        $this->addSql('ALTER TABLE hobby ADD title VARCHAR(255) NOT NULL, DROP slug');
        $this->addSql('DROP INDEX UNIQ_4FB4DC80989D9B62 ON hobby_category');
        $this->addSql('ALTER TABLE hobby_category DROP slug');
    }
}
