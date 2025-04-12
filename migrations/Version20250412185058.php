<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412185058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_subject ADD hobby_id INT DEFAULT NULL, ADD interest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_subject ADD CONSTRAINT FK_7DCE6A76322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id)');
        $this->addSql('ALTER TABLE group_subject ADD CONSTRAINT FK_7DCE6A765A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id)');
        $this->addSql('CREATE INDEX IDX_7DCE6A76322B2123 ON group_subject (hobby_id)');
        $this->addSql('CREATE INDEX IDX_7DCE6A765A95FF89 ON group_subject (interest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_subject DROP FOREIGN KEY FK_7DCE6A76322B2123');
        $this->addSql('ALTER TABLE group_subject DROP FOREIGN KEY FK_7DCE6A765A95FF89');
        $this->addSql('DROP INDEX IDX_7DCE6A76322B2123 ON group_subject');
        $this->addSql('DROP INDEX IDX_7DCE6A765A95FF89 ON group_subject');
        $this->addSql('ALTER TABLE group_subject DROP hobby_id, DROP interest_id');
    }
}
