<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307055821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE INDEX IDX_D0D326075C25C74D ON user_profile_user_profile (user_profile_source)');
        $this->addSql('CREATE INDEX IDX_D0D3260745C097C2 ON user_profile_user_profile (user_profile_target)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D326075C25C74D');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D3260745C097C2');
        $this->addSql('DROP INDEX IDX_D0D326075C25C74D ON user_profile_user_profile');
        $this->addSql('DROP INDEX IDX_D0D3260745C097C2 ON user_profile_user_profile');
    }
}
