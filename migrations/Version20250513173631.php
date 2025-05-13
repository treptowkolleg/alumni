<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513173631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gruschel (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, send_by_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_12BFC140A76ED395 (user_id), INDEX IDX_12BFC140C3852542 (send_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gruschel ADD CONSTRAINT FK_12BFC140A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gruschel ADD CONSTRAINT FK_12BFC140C3852542 FOREIGN KEY (send_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gruschel DROP FOREIGN KEY FK_12BFC140A76ED395');
        $this->addSql('ALTER TABLE gruschel DROP FOREIGN KEY FK_12BFC140C3852542');
        $this->addSql('DROP TABLE gruschel');
    }
}
