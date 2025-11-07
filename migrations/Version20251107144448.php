<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107144448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, author_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, content LONGTEXT NOT NULL, is_published TINYINT(1) NOT NULL, created DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_lead_post TINYINT(1) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, blog_post_image VARCHAR(255) DEFAULT NULL, blog_post_audio VARCHAR(255) DEFAULT NULL, image_cite VARCHAR(255) DEFAULT NULL, image_city_url VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', audio_name VARCHAR(255) DEFAULT NULL, audio_original_name VARCHAR(255) DEFAULT NULL, audio_mime_type VARCHAR(255) DEFAULT NULL, audio_size INT DEFAULT NULL, audio_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_BA5AE01D989D9B62 (slug), INDEX IDX_BA5AE01DC54C8C93 (type_id), INDEX IDX_BA5AE01DF675F31B (author_id), INDEX IDX_BA5AE01D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CA275A0C989D9B62 (slug), INDEX IDX_CA275A0C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id VARCHAR(12) NOT NULL, owner_id INT DEFAULT NULL, participant_id INT DEFAULT NULL, INDEX IDX_659DF2AA7E3C61F9 (owner_id), INDEX IDX_659DF2AA9D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id VARCHAR(12) NOT NULL, sender_id INT DEFAULT NULL, chat_id VARCHAR(12) DEFAULT NULL, message LONGTEXT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) DEFAULT NULL, subject VARCHAR(50) DEFAULT NULL, INDEX IDX_FAB3FC16F624B39D (sender_id), INDEX IDX_FAB3FC161A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE direct_message (id VARCHAR(12) NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, content LONGTEXT NOT NULL, is_read TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, is_pinned TINYINT(1) NOT NULL, title VARCHAR(100) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1416AF93F624B39D (sender_id), INDEX IDX_1416AF93E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, website VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, contact_person VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 (slug), INDEX IDX_3BAE0AA7C54C8C93 (type_id), INDEX IDX_3BAE0AA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_school (event_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_CB07ADE971F7E88B (event_id), INDEX IDX_CB07ADE9C32A47EE (school_id), PRIMARY KEY(event_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_hobby (event_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_787F421B71F7E88B (event_id), INDEX IDX_787F421B322B2123 (hobby_id), PRIMARY KEY(event_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_interest (event_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_2AD2F3B771F7E88B (event_id), INDEX IDX_2AD2F3B75A95FF89 (interest_id), PRIMARY KEY(event_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_subject (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, hobby_id INT DEFAULT NULL, interest_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, description VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', private TINYINT(1) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_7DCE6A76989D9B62 (slug), INDEX IDX_7DCE6A767E3C61F9 (owner_id), INDEX IDX_7DCE6A76322B2123 (hobby_id), INDEX IDX_7DCE6A765A95FF89 (interest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gruschel (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, send_by_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_12BFC140A76ED395 (user_id), INDEX IDX_12BFC140C3852542 (send_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_3964F337989D9B62 (slug), INDEX IDX_3964F33712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobby_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_4FB4DC80989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inbound (id INT AUTO_INCREMENT NOT NULL, sender VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, html LONGTEXT DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', departement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_6C3E1A67989D9B62 (slug), INDEX IDX_6C3E1A6712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_7A9F8C01989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_B12216877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_7E8585C8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_school (newsletter_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_43C481F222DB1917 (newsletter_id), INDEX IDX_43C481F2C32A47EE (school_id), PRIMARY KEY(newsletter_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_queue (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, receiver_email VARCHAR(255) NOT NULL, send_date DATETIME NOT NULL, send TINYINT(1) NOT NULL, user_count INT NOT NULL, token VARCHAR(64) DEFAULT NULL, INDEX IDX_F59033975DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_template (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_date DATETIME NOT NULL, period INT NOT NULL, period_unit VARCHAR(255) NOT NULL, use_all_receivers TINYINT(1) NOT NULL, show_events TINYINT(1) NOT NULL, show_recent_posts TINYINT(1) NOT NULL, show_offers TINYINT(1) NOT NULL, show_recent_news TINYINT(1) NOT NULL, show_recent_pins TINYINT(1) NOT NULL, INDEX IDX_4A62D9E9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_template_school (newsletter_template_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_B4FF0F6F33345077 (newsletter_template_id), INDEX IDX_B4FF0F6FC32A47EE (school_id), PRIMARY KEY(newsletter_template_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_7226793E989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_offer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type_id INT DEFAULT NULL, school_id INT DEFAULT NULL, job_type_id INT DEFAULT NULL, salary_level_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', county VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, contact_person VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, contact_phone VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_85E098FA989D9B62 (slug), INDEX IDX_85E098FAA76ED395 (user_id), INDEX IDX_85E098FAC54C8C93 (type_id), INDEX IDX_85E098FAC32A47EE (school_id), INDEX IDX_85E098FA5FA33B08 (job_type_id), INDEX IDX_85E098FACDF4C1A6 (salary_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_offer_hobby (person_offer_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_D7A2D156C780D1D9 (person_offer_id), INDEX IDX_D7A2D156322B2123 (hobby_id), PRIMARY KEY(person_offer_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_offer_interest (person_offer_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_F09E9A3BC780D1D9 (person_offer_id), INDEX IDX_F09E9A3B5A95FF89 (interest_id), PRIMARY KEY(person_offer_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pinboard_entry (id INT AUTO_INCREMENT NOT NULL, user_profile_id INT DEFAULT NULL, writer_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_91CA6E956B9DD454 (user_profile_id), INDEX IDX_91CA6E951BC7E6B6 (writer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_level (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_F0D2FB377153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, city VARCHAR(255) DEFAULT NULL, district VARCHAR(255) DEFAULT NULL, sub_district VARCHAR(255) DEFAULT NULL, plz VARCHAR(5) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, bsn VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, lat VARCHAR(255) DEFAULT NULL, lon VARCHAR(255) DEFAULT NULL, title_sound_ex VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, county VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F99EDABB989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_post (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, text LONGTEXT NOT NULL, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C840E856727ACA70 (parent_id), INDEX IDX_C840E8567E3C61F9 (owner_id), INDEX IDX_C840E85623EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_answer (id INT AUTO_INCREMENT NOT NULL, survey_id INT DEFAULT NULL, user_id INT DEFAULT NULL, answer TINYINT(1) NOT NULL, INDEX IDX_F2D38249B3FE509D (survey_id), INDEX IDX_F2D38249A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE university (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, short_title VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, county VARCHAR(100) NOT NULL, city VARCHAR(255) NOT NULL, plz VARCHAR(5) NOT NULL, street VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A07A85EC989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, slug VARCHAR(128) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, user_type VARCHAR(255) NOT NULL, has_school TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', firstname_sound_ex VARCHAR(255) DEFAULT NULL, lastname_sound_ex VARCHAR(255) DEFAULT NULL, has_newsletter TINYINT(1) DEFAULT NULL, is_contactable TINYINT(1) DEFAULT NULL, is_events_visible TINYINT(1) DEFAULT NULL, has_pinnboard TINYINT(1) DEFAULT NULL, message_visibility_scope VARCHAR(20) DEFAULT \'same_school\' NOT NULL, notify_new_mail TINYINT(1) DEFAULT NULL, gruschel_is_active TINYINT(1) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), INDEX IDX_8D93D649C32A47EE (school_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_image_upload (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, user_image VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_386CC4D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, user_university_id INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', in_year VARCHAR(4) DEFAULT NULL, out_year VARCHAR(4) DEFAULT NULL, current_profession VARCHAR(255) DEFAULT NULL, current_company VARCHAR(255) DEFAULT NULL, studium VARCHAR(255) DEFAULT NULL, university VARCHAR(255) DEFAULT NULL, portfolio_link VARCHAR(255) DEFAULT NULL, network_state VARCHAR(255) DEFAULT NULL, hobbies JSON DEFAULT NULL, favorite_school_subjects JSON DEFAULT NULL, interests JSON DEFAULT NULL, about LONGTEXT DEFAULT NULL, performance_course JSON DEFAULT NULL, exam_type VARCHAR(255) DEFAULT NULL, tags JSON DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_D95AB405A76ED395 (user_id), INDEX IDX_D95AB405DE13B528 (user_university_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_user_profile (user_profile_source INT NOT NULL, user_profile_target INT NOT NULL, INDEX IDX_D0D326075C25C74D (user_profile_source), INDEX IDX_D0D3260745C097C2 (user_profile_target), PRIMARY KEY(user_profile_source, user_profile_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_hobby (user_profile_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_203EC1B56B9DD454 (user_profile_id), INDEX IDX_203EC1B5322B2123 (hobby_id), PRIMARY KEY(user_profile_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_interest (user_profile_id INT NOT NULL, interest_id INT NOT NULL, INDEX IDX_E841390B6B9DD454 (user_profile_id), INDEX IDX_E841390B5A95FF89 (interest_id), PRIMARY KEY(user_profile_id, interest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DC54C8C93 FOREIGN KEY (type_id) REFERENCES blog_type (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES blog_post_category (id)');
        $this->addSql('ALTER TABLE blog_post_category ADD CONSTRAINT FK_CA275A0C727ACA70 FOREIGN KEY (parent_id) REFERENCES blog_post_category (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA9D1C3019 FOREIGN KEY (participant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE971F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_school ADD CONSTRAINT FK_CB07ADE9C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_hobby ADD CONSTRAINT FK_787F421B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_hobby ADD CONSTRAINT FK_787F421B322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_interest ADD CONSTRAINT FK_2AD2F3B771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_interest ADD CONSTRAINT FK_2AD2F3B75A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_subject ADD CONSTRAINT FK_7DCE6A767E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_subject ADD CONSTRAINT FK_7DCE6A76322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id)');
        $this->addSql('ALTER TABLE group_subject ADD CONSTRAINT FK_7DCE6A765A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id)');
        $this->addSql('ALTER TABLE gruschel ADD CONSTRAINT FK_12BFC140A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gruschel ADD CONSTRAINT FK_12BFC140C3852542 FOREIGN KEY (send_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hobby ADD CONSTRAINT FK_3964F33712469DE2 FOREIGN KEY (category_id) REFERENCES hobby_category (id)');
        $this->addSql('ALTER TABLE interest ADD CONSTRAINT FK_6C3E1A6712469DE2 FOREIGN KEY (category_id) REFERENCES interest_category (id)');
        $this->addSql('ALTER TABLE newsletter ADD CONSTRAINT FK_7E8585C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F222DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_school ADD CONSTRAINT FK_43C481F2C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_queue ADD CONSTRAINT FK_F59033975DA0FB8 FOREIGN KEY (template_id) REFERENCES newsletter_template (id)');
        $this->addSql('ALTER TABLE newsletter_template ADD CONSTRAINT FK_4A62D9E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE newsletter_template_school ADD CONSTRAINT FK_B4FF0F6F33345077 FOREIGN KEY (newsletter_template_id) REFERENCES newsletter_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_template_school ADD CONSTRAINT FK_B4FF0F6FC32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FAC54C8C93 FOREIGN KEY (type_id) REFERENCES offer_type (id)');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FAC32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FA5FA33B08 FOREIGN KEY (job_type_id) REFERENCES job_type (id)');
        $this->addSql('ALTER TABLE person_offer ADD CONSTRAINT FK_85E098FACDF4C1A6 FOREIGN KEY (salary_level_id) REFERENCES salary_level (id)');
        $this->addSql('ALTER TABLE person_offer_hobby ADD CONSTRAINT FK_D7A2D156C780D1D9 FOREIGN KEY (person_offer_id) REFERENCES person_offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_hobby ADD CONSTRAINT FK_D7A2D156322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_interest ADD CONSTRAINT FK_F09E9A3BC780D1D9 FOREIGN KEY (person_offer_id) REFERENCES person_offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_offer_interest ADD CONSTRAINT FK_F09E9A3B5A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pinboard_entry ADD CONSTRAINT FK_91CA6E956B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE pinboard_entry ADD CONSTRAINT FK_91CA6E951BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E856727ACA70 FOREIGN KEY (parent_id) REFERENCES subject_post (id)');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E8567E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subject_post ADD CONSTRAINT FK_C840E85623EDC87 FOREIGN KEY (subject_id) REFERENCES group_subject (id)');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE user_image_upload ADD CONSTRAINT FK_386CC4D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405DE13B528 FOREIGN KEY (user_university_id) REFERENCES university (id)');
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D326075C25C74D FOREIGN KEY (user_profile_source) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_user_profile ADD CONSTRAINT FK_D0D3260745C097C2 FOREIGN KEY (user_profile_target) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_hobby ADD CONSTRAINT FK_203EC1B56B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_hobby ADD CONSTRAINT FK_203EC1B5322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_interest ADD CONSTRAINT FK_E841390B6B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_profile_interest ADD CONSTRAINT FK_E841390B5A95FF89 FOREIGN KEY (interest_id) REFERENCES interest (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DC54C8C93');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DF675F31B');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE blog_post_category DROP FOREIGN KEY FK_CA275A0C727ACA70');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA7E3C61F9');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA9D1C3019');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16F624B39D');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93F624B39D');
        $this->addSql('ALTER TABLE direct_message DROP FOREIGN KEY FK_1416AF93E92F8F78');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A76ED395');
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE971F7E88B');
        $this->addSql('ALTER TABLE event_school DROP FOREIGN KEY FK_CB07ADE9C32A47EE');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE event_hobby DROP FOREIGN KEY FK_787F421B71F7E88B');
        $this->addSql('ALTER TABLE event_hobby DROP FOREIGN KEY FK_787F421B322B2123');
        $this->addSql('ALTER TABLE event_interest DROP FOREIGN KEY FK_2AD2F3B771F7E88B');
        $this->addSql('ALTER TABLE event_interest DROP FOREIGN KEY FK_2AD2F3B75A95FF89');
        $this->addSql('ALTER TABLE group_subject DROP FOREIGN KEY FK_7DCE6A767E3C61F9');
        $this->addSql('ALTER TABLE group_subject DROP FOREIGN KEY FK_7DCE6A76322B2123');
        $this->addSql('ALTER TABLE group_subject DROP FOREIGN KEY FK_7DCE6A765A95FF89');
        $this->addSql('ALTER TABLE gruschel DROP FOREIGN KEY FK_12BFC140A76ED395');
        $this->addSql('ALTER TABLE gruschel DROP FOREIGN KEY FK_12BFC140C3852542');
        $this->addSql('ALTER TABLE hobby DROP FOREIGN KEY FK_3964F33712469DE2');
        $this->addSql('ALTER TABLE interest DROP FOREIGN KEY FK_6C3E1A6712469DE2');
        $this->addSql('ALTER TABLE newsletter DROP FOREIGN KEY FK_7E8585C8A76ED395');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F222DB1917');
        $this->addSql('ALTER TABLE newsletter_school DROP FOREIGN KEY FK_43C481F2C32A47EE');
        $this->addSql('ALTER TABLE newsletter_queue DROP FOREIGN KEY FK_F59033975DA0FB8');
        $this->addSql('ALTER TABLE newsletter_template DROP FOREIGN KEY FK_4A62D9E9A76ED395');
        $this->addSql('ALTER TABLE newsletter_template_school DROP FOREIGN KEY FK_B4FF0F6F33345077');
        $this->addSql('ALTER TABLE newsletter_template_school DROP FOREIGN KEY FK_B4FF0F6FC32A47EE');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FAA76ED395');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FAC54C8C93');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FAC32A47EE');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FA5FA33B08');
        $this->addSql('ALTER TABLE person_offer DROP FOREIGN KEY FK_85E098FACDF4C1A6');
        $this->addSql('ALTER TABLE person_offer_hobby DROP FOREIGN KEY FK_D7A2D156C780D1D9');
        $this->addSql('ALTER TABLE person_offer_hobby DROP FOREIGN KEY FK_D7A2D156322B2123');
        $this->addSql('ALTER TABLE person_offer_interest DROP FOREIGN KEY FK_F09E9A3BC780D1D9');
        $this->addSql('ALTER TABLE person_offer_interest DROP FOREIGN KEY FK_F09E9A3B5A95FF89');
        $this->addSql('ALTER TABLE pinboard_entry DROP FOREIGN KEY FK_91CA6E956B9DD454');
        $this->addSql('ALTER TABLE pinboard_entry DROP FOREIGN KEY FK_91CA6E951BC7E6B6');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E856727ACA70');
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E8567E3C61F9');
        $this->addSql('ALTER TABLE subject_post DROP FOREIGN KEY FK_C840E85623EDC87');
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D38249B3FE509D');
        $this->addSql('ALTER TABLE survey_answer DROP FOREIGN KEY FK_F2D38249A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C32A47EE');
        $this->addSql('ALTER TABLE user_image_upload DROP FOREIGN KEY FK_386CC4D5A76ED395');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405DE13B528');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D326075C25C74D');
        $this->addSql('ALTER TABLE user_profile_user_profile DROP FOREIGN KEY FK_D0D3260745C097C2');
        $this->addSql('ALTER TABLE user_profile_hobby DROP FOREIGN KEY FK_203EC1B56B9DD454');
        $this->addSql('ALTER TABLE user_profile_hobby DROP FOREIGN KEY FK_203EC1B5322B2123');
        $this->addSql('ALTER TABLE user_profile_interest DROP FOREIGN KEY FK_E841390B6B9DD454');
        $this->addSql('ALTER TABLE user_profile_interest DROP FOREIGN KEY FK_E841390B5A95FF89');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_post_category');
        $this->addSql('DROP TABLE blog_type');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE direct_message');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_school');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE event_hobby');
        $this->addSql('DROP TABLE event_interest');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE group_subject');
        $this->addSql('DROP TABLE gruschel');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE hobby_category');
        $this->addSql('DROP TABLE inbound');
        $this->addSql('DROP TABLE interest');
        $this->addSql('DROP TABLE interest_category');
        $this->addSql('DROP TABLE job_type');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE newsletter_school');
        $this->addSql('DROP TABLE newsletter_queue');
        $this->addSql('DROP TABLE newsletter_template');
        $this->addSql('DROP TABLE newsletter_template_school');
        $this->addSql('DROP TABLE offer_type');
        $this->addSql('DROP TABLE person_offer');
        $this->addSql('DROP TABLE person_offer_hobby');
        $this->addSql('DROP TABLE person_offer_interest');
        $this->addSql('DROP TABLE pinboard_entry');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE salary_level');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE subject_post');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE survey_answer');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE university');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_image_upload');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE user_profile_user_profile');
        $this->addSql('DROP TABLE user_profile_hobby');
        $this->addSql('DROP TABLE user_profile_interest');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
