<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307060150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D326075C25C74D FOREIGN KEY (user_profile_source) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D3260745C097C2 FOREIGN KEY (user_profile_target) REFERENCES user_profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D326075C25C74D');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D3260745C097C2');
    }
}
