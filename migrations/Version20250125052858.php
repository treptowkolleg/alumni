<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125052858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_school (event_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_CB07ADE971F7E88B (event_id), INDEX IDX_CB07ADE9C32A47EE (school_id), PRIMARY KEY(event_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_school (newsletter_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_43C481F222DB1917 (newsletter_id), INDEX IDX_43C481F2C32A47EE (school_id), PRIMARY KEY(newsletter_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE971F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE9C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F222DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F2C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A76ED395 ON event (user_id)');
        $this->addSql('ALTER TABLE inbound ADD departement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7E8585C8A76ED395 ON newsletter (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE971F7E88B');
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE9C32A47EE');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F222DB1917');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F2C32A47EE');
        $this->addSql('DROP TABLE event_school');
        $this->addSql('DROP TABLE newsletter_school');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('DROP INDEX IDX_3BAE0AA7A76ED395 ON event');
        $this->addSql('ALTER TABLE event DROP user_id');
        $this->addSql('ALTER TABLE inbound DROP departement');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8A76ED395');
        $this->addSql('DROP INDEX IDX_7E8585C8A76ED395 ON newsletter');
        $this->addSql('ALTER TABLE newsletter DROP user_id');
    }
}
