<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916141905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, language_id INT NOT NULL, visible TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, position INT NOT NULL, thumbnail VARCHAR(255) NOT NULL, pitch LONGTEXT NOT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_BFDD3168C54C8C93 (type_id), INDEX IDX_BFDD316882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_languages (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_types (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168C54C8C93 FOREIGN KEY (type_id) REFERENCES articles_types (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316882F1BAF4 FOREIGN KEY (language_id) REFERENCES articles_languages (id)');
        $this->addSql('DROP TABLE article_type');
        $this->addSql('DROP TABLE article_language');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE article_language (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168C54C8C93');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316882F1BAF4');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE articles_languages');
        $this->addSql('DROP TABLE articles_types');
    }
}
