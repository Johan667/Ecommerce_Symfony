<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022100835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6C8A81A9');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A14A76ED395');
        $this->addSql('ALTER TABLE star CHANGE product_id product_id INT NOT NULL, CHANGE note note SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A14A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6C8A81A9');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A14A76ED395');
        $this->addSql('ALTER TABLE star CHANGE product_id product_id INT DEFAULT NULL, CHANGE note note SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
