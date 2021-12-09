<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211209121915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_music_gender (event_id INT NOT NULL, music_gender_id INT NOT NULL, INDEX IDX_BF8CC34871F7E88B (event_id), INDEX IDX_BF8CC348D5C23641 (music_gender_id), PRIMARY KEY(event_id, music_gender_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_music_gender ADD CONSTRAINT FK_BF8CC34871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_music_gender ADD CONSTRAINT FK_BF8CC348D5C23641 FOREIGN KEY (music_gender_id) REFERENCES music_gender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event CHANGE end_date end_date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_music_gender');
        $this->addSql('ALTER TABLE event CHANGE end_date end_date DATE DEFAULT NULL');
    }
}
