<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306154602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announce_category (announce_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7AE77166F5DA3DE (announce_id), INDEX IDX_7AE771612469DE2 (category_id), PRIMARY KEY(announce_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announce_category ADD CONSTRAINT FK_7AE77166F5DA3DE FOREIGN KEY (announce_id) REFERENCES announce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE announce_category ADD CONSTRAINT FK_7AE771612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announce_category DROP FOREIGN KEY FK_7AE77166F5DA3DE');
        $this->addSql('ALTER TABLE announce_category DROP FOREIGN KEY FK_7AE771612469DE2');
        $this->addSql('DROP TABLE announce_category');
    }
}
