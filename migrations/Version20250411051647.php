<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411051647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_offer_hobby (person_offer_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_D7A2D156C780D1D9 (person_offer_id), INDEX IDX_D7A2D156322B2123 (hobby_id), PRIMARY KEY(person_offer_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_offer_interest (person_offer_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_F09E9A3BC780D1D9 (person_offer_id), INDEX IDX_F09E9A3B5A95FF89 (interest_id), PRIMARY KEY(person_offer_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_offer_hobby ADD CONSTRAINT FK_D7A2D156C780D1D9 FOREIGN KEY (person_offer_id) REFERENCES person_offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_hobby ADD CONSTRAINT FK_D7A2D156322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_interest ADD CONSTRAINT FK_F09E9A3BC780D1D9 FOREIGN KEY (person_offer_id) REFERENCES person_offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_interest ADD CONSTRAINT FK_F09E9A3B5A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_offer_hobby DROP FOREIGN KEY FK_D7A2D156C780D1D9');
        $this->addSql('ALTER TABLE person_offer_hobby DROP FOREIGN KEY FK_D7A2D156322B2123');
        $this->addSql('ALTER TABLE person_offer_interest DROP FOREIGN KEY FK_F09E9A3BC780D1D9');
        $this->addSql('ALTER TABLE person_offer_interest DROP FOREIGN KEY FK_F09E9A3B5A95FF89');
        $this->addSql('DROP TABLE person_offer_hobby');
        $this->addSql('DROP TABLE person_offer_interest');
    }
}
