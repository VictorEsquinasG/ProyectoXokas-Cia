<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210091411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego ADD ancho_tablero INT NOT NULL, ADD largo_tablero INT NOT NULL, ADD descripcion LONGTEXT DEFAULT NULL, DROP tamanio_tablero');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego ADD tamanio_tablero LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP ancho_tablero, DROP largo_tablero, DROP descripcion');
    }
}
