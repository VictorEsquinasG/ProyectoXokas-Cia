<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131072918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE distribucion (id INT AUTO_INCREMENT NOT NULL, mesa_id_id INT NOT NULL, posicion_x INT NOT NULL, posicion_y INT NOT NULL, fecha DATETIME NOT NULL, reservada TINYINT(1) DEFAULT NULL, INDEX IDX_848A6F79AC72117 (mesa_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fechas_festivos (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_848A6F79AC72117');
        $this->addSql('DROP INDEX idx_848a6f79ac72117 ON distribucion');
        $this->addSql('CREATE INDEX IDX_698658A79AC72117 ON distribucion (mesa_id_id)');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_848A6F79AC72117 FOREIGN KEY (mesa_id_id) REFERENCES mesa (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fechas_festivos');
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_698658A79AC72117');
        $this->addSql('DROP INDEX idx_698658a79ac72117 ON distribucion');
        $this->addSql('CREATE INDEX IDX_848A6F79AC72117 ON distribucion (mesa_id_id)');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_698658A79AC72117 FOREIGN KEY (mesa_id_id) REFERENCES mesa (id)');
    }
}
