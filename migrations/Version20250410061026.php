<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410061026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_profile_hobby (user_profile_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_203EC1B56B9DD454 (user_profile_id), INDEX IDX_203EC1B5322B2123 (hobby_id), PRIMARY KEY(user_profile_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_interest (user_profile_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_E841390B6B9DD454 (user_profile_id), INDEX IDX_E841390B5A95FF89 (interest_id), PRIMARY KEY(user_profile_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_profile_hobby ADD CONSTRAINT FK_203EC1B56B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_hobby ADD CONSTRAINT FK_203EC1B5322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_interest ADD CONSTRAINT FK_E841390B6B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_interest ADD CONSTRAINT FK_E841390B5A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile ADD user_university_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405DE13B528 FOREIGN KEY (user_university_id) REFERENCES university (id)');
        $this->addSql('CREATE INDEX IDX_D95AB405DE13B528 ON user_profile (user_university_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile_hobby DROP FOREIGN KEY FK_203EC1B56B9DD454');
        $this->addSql('ALTER TABLE user_profile_hobby DROP FOREIGN KEY FK_203EC1B5322B2123');
        $this->addSql('ALTER TABLE user_profile_interest DROP FOREIGN KEY FK_E841390B6B9DD454');
        $this->addSql('ALTER TABLE user_profile_interest DROP FOREIGN KEY FK_E841390B5A95FF89');
        $this->addSql('DROP TABLE user_profile_hobby');
        $this->addSql('DROP TABLE user_profile_interest');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405DE13B528');
        $this->addSql('DROP INDEX IDX_D95AB405DE13B528 ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP user_university_id');
    }
}
