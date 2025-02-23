<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211193748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_register (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, lesson_id INT DEFAULT NULL, purchased_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price_purchased INT NOT NULL, INDEX IDX_763B5346A76ED395 (user_id), INDEX IDX_763B5346CDF80196 (lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_register ADD CONSTRAINT FK_763B5346A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE purchase_register ADD CONSTRAINT FK_763B5346CDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_register DROP FOREIGN KEY FK_763B5346A76ED395');
        $this->addSql('ALTER TABLE purchase_register DROP FOREIGN KEY FK_763B5346CDF80196');
        $this->addSql('DROP TABLE purchase_register');
    }
}
