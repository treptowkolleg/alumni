<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412211456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_post ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E85623EDC87 FOREIGN KEY (subject_id) REFERENCES group_subject (id)');
        $this->addSql('CREATE INDEX IDX_C840E85623EDC87 ON subject_post (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E85623EDC87');
        $this->addSql('DROP INDEX IDX_C840E85623EDC87 ON subject_post');
        $this->addSql('ALTER TABLE subject_post DROP subject_id');
    }
}
