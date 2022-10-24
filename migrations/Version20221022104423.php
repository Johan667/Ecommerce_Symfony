<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022104423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE star CHANGE product_id product_id INT NOT NULL, CHANGE note note SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A14A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A14A76ED395');
        $this->addSql('ALTER TABLE star CHANGE product_id product_id INT DEFAULT NULL, CHANGE note note SMALLINT DEFAULT NULL');
    }
}
