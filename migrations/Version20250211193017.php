<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211193017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE connection_register (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, connected_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ip_address VARCHAR(255) NOT NULL, INDEX IDX_FDE53B80A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection_register ADD CONSTRAINT FK_FDE53B80A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE connection_register DROP FOREIGN KEY FK_FDE53B80A76ED395');
        $this->addSql('DROP TABLE connection_register');
    }
}
