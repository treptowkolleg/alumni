<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228232352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE school ADD slug VARCHAR(128) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F222DB1917');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F2C32A47EE');
        $this->addSql('DROP INDEX UNIQ_F99EDABB989D9B62 ON school');
        $this->addSql('ALTER TABLE school DROP slug');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('ALTER TABLE user_profile CHANGE hobbies hobbies JSON DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE favorite_school_subjects favorite_school_subjects JSON DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE interests interests JSON DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE performance_course performance_course JSON DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE tags tags JSON DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D326075C25C74D');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D3260745C097C2');
    }
}
