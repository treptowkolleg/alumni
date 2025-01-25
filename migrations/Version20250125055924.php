<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125055924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE school ADD title_sound_ex VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD firstname_sound_ex VARCHAR(255) DEFAULT NULL, ADD lastname_sound_ex VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE school DROP title_sound_ex');
        $this->addSql('ALTER TABLE user DROP firstname_sound_ex, DROP lastname_sound_ex');
    }
}
