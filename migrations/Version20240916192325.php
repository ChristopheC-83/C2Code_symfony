<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916192325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, languages_id INT NOT NULL, types_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_BFDD31685D237A9A (languages_id), INDEX IDX_BFDD31688EB23357 (types_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31685D237A9A FOREIGN KEY (languages_id) REFERENCES languages (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD31688EB23357 FOREIGN KEY (types_id) REFERENCES types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD31685D237A9A');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD31688EB23357');
        $this->addSql('DROP TABLE articles');
    }
}