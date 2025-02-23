<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209153110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_lesson (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, lesson_id INT DEFAULT NULL, purchased_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', youtube_id VARCHAR(255) NOT NULL, secure_token LONGTEXT NOT NULL, purchased_price INT NOT NULL, INDEX IDX_9D266FCEA76ED395 (user_id), INDEX IDX_9D266FCECDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_lesson ADD CONSTRAINT FK_9D266FCECDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lessons ADD price INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCEA76ED395');
        $this->addSql('ALTER TABLE user_lesson DROP FOREIGN KEY FK_9D266FCECDF80196');
        $this->addSql('DROP TABLE user_lesson');
        $this->addSql('ALTER TABLE lessons DROP price');
    }
}
