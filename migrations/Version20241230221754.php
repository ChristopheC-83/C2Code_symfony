<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230221754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments_lessons (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, lesson_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, INDEX IDX_328BEB15A76ED395 (user_id), INDEX IDX_328BEB15CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_lessons ADD CONSTRAINT FK_328BEB15A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comments_lessons ADD CONSTRAINT FK_328BEB15CDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_lessons DROP FOREIGN KEY FK_328BEB15A76ED395');
        $this->addSql('ALTER TABLE comments_lessons DROP FOREIGN KEY FK_328BEB15CDF80196');
        $this->addSql('DROP TABLE comments_lessons');
    }
}
