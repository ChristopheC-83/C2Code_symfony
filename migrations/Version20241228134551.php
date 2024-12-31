<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228134551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, type_id INT DEFAULT NULL, is_premium TINYINT(1) NOT NULL, is_visible TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', position INT DEFAULT NULL, time TIME DEFAULT NULL COMMENT \'(DC2Type:time_immutable)\', INDEX IDX_3F4218D9591CC992 (course_id), INDEX IDX_3F4218D9C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9C54C8C93 FOREIGN KEY (type_id) REFERENCES lessons_types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9591CC992');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9C54C8C93');
        $this->addSql('DROP TABLE lessons');
    }
}
