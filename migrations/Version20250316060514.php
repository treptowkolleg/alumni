<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316060514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletter_template (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_date DATETIME NOT NULL, period INT NOT NULL, period_unit VARCHAR(255) NOT NULL, use_all_receivers TINYINT(1) NOT NULL, show_events TINYINT(1) NOT NULL, show_recent_posts TINYINT(1) NOT NULL, show_offers TINYINT(1) NOT NULL, show_recent_news TINYINT(1) NOT NULL, show_recent_pins TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_template_school (newsletter_template_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_B4FF0F6F33345077 (newsletter_template_id), INDEX IDX_B4FF0F6FC32A47EE (school_id), PRIMARY KEY(newsletter_template_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_template_school ADD CONSTRAINT FK_B4FF0F6F33345077 FOREIGN KEY (newsletter_template_id) REFERENCES newsletter_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_template_school ADD CONSTRAINT FK_B4FF0F6FC32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_template_school DROP FOREIGN KEY FK_B4FF0F6F33345077');
        $this->addSql('ALTER TABLE newsletter_template_school DROP FOREIGN KEY FK_B4FF0F6FC32A47EE');
        $this->addSql('DROP TABLE newsletter_template');
        $this->addSql('DROP TABLE newsletter_template_school');
    }
}
