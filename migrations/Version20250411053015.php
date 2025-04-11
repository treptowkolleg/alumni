<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411053015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_hobby (event_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_787F421B71F7E88B (event_id), INDEX IDX_787F421B322B2123 (hobby_id), PRIMARY KEY(event_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_interest (event_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_2AD2F3B771F7E88B (event_id), INDEX IDX_2AD2F3B75A95FF89 (interest_id), PRIMARY KEY(event_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_hobby ADD CONSTRAINT FK_787F421B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_hobby ADD CONSTRAINT FK_787F421B322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_interest ADD CONSTRAINT FK_2AD2F3B771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_interest ADD CONSTRAINT FK_2AD2F3B75A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_hobby DROP FOREIGN KEY FK_787F421B71F7E88B');
        $this->addSql('ALTER TABLE event_hobby DROP FOREIGN KEY FK_787F421B322B2123');
        $this->addSql('ALTER TABLE event_interest DROP FOREIGN KEY FK_2AD2F3B771F7E88B');
        $this->addSql('ALTER TABLE event_interest DROP FOREIGN KEY FK_2AD2F3B75A95FF89');
        $this->addSql('DROP TABLE event_hobby');
        $this->addSql('DROP TABLE event_interest');
    }
}
