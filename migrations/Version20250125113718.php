<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125113718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, content LONGTEXT NOT NULL, is_published TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_lead_post TINYINT(1) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', audio_name VARCHAR(255) DEFAULT NULL, audio_original_name VARCHAR(255) DEFAULT NULL, audio_mime_type VARCHAR(255) DEFAULT NULL, audio_size INT DEFAULT NULL, audio_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_BA5AE01D989D9B62 (slug), INDEX IDX_BA5AE01DC54C8C93 (type_id), INDEX IDX_BA5AE01DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id VARCHAR(12) NOT NULL, owner_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, INDEX IDX_659DF2AA7E3C61F9 (owner_id), INDEX IDX_659DF2AA9D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id VARCHAR(12) NOT NULL, sender_id INT DEFAULT NULL, chat_id VARCHAR(12) DEFAULT NULL, message LONGTEXT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) DEFAULT NULL, subject VARCHAR(50) DEFAULT NULL, INDEX IDX_FAB3FC16F624B39D (sender_id), INDEX IDX_FAB3FC161A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, website VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, contact_person VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 (slug), INDEX IDX_3BAE0AA7C54C8C93 (type_id), INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_school (event_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_CB07ADE971F7E88B (event_id), INDEX IDX_CB07ADE9C32A47EE (school_id), PRIMARY KEY(event_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inbound (id INT AUTO_INCREMENT NOT NULL, sender VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, html LONGTEXT DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', departement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_7E8585C8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_school (newsletter_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_43C481F222DB1917 (newsletter_id), INDEX IDX_43C481F2C32A47EE (school_id), PRIMARY KEY(newsletter_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', in_year VARCHAR(4) DEFAULT NULL, out_year VARCHAR(4) DEFAULT NULL, current_profession VARCHAR(255) DEFAULT NULL, current_company VARCHAR(255) DEFAULT NULL, studium VARCHAR(255) DEFAULT NULL, university VARCHAR(255) DEFAULT NULL, portfolio_link VARCHAR(255) DEFAULT NULL, network_state VARCHAR(255) DEFAULT NULL, hobbies JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', favorite_school_subjects JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', interests JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', about LONGTEXT DEFAULT NULL, performance_course JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', exam_type VARCHAR(255) DEFAULT NULL, tags JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', display_name VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_D95AB405A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_user_profile (user_profile_source INT NOT NULL, user_profile_target INT NOT NULL, INDEX IDX_D0D326075C25C74D (user_profile_source), INDEX IDX_D0D3260745C097C2 (user_profile_target), PRIMARY KEY(user_profile_source, user_profile_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DC54C8C93 FOREIGN KEY (type_id) REFERENCES blog_type (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA9D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE971F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE9C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F222DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F2C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D326075C25C74D FOREIGN KEY (user_profile_source) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D3260745C097C2 FOREIGN KEY (user_profile_target) REFERENCES user_profile (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DC54C8C93');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DF675F31B');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA7E3C61F9');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA9D1C3019');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16F624B39D');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE971F7E88B');
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE9C32A47EE');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8A76ED395');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F222DB1917');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F2C32A47EE');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D326075C25C74D');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D3260745C097C2');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_type');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_school');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE inbound');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE newsletter_school');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE user_profile_user_profile');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
