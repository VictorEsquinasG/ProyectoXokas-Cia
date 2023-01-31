<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130174218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('DROP TABLE distribuicion');
        // $this->addSql('CREATE TABLE distribucion (id INT AUTO_INCREMENT NOT NULL, mesa_id_id INT NOT NULL, posicion_x INT NOT NULL, posicion_y INT NOT NULL, fecha DATETIME NOT NULL, reservada TINYINT(1) DEFAULT NULL, INDEX IDX_848A6F79AC72117 (mesa_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, num_max_asistentes INT NOT NULL, nombre VARCHAR(55) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, min_jugadores INT NOT NULL, max_jugadores INT NOT NULL, tamanio_tablero LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego_evento (juego_id INT NOT NULL, evento_id INT NOT NULL, INDEX IDX_131B1E0113375255 (juego_id), INDEX IDX_131B1E0187A5F842 (evento_id), PRIMARY KEY(juego_id, evento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mesa (id INT AUTO_INCREMENT NOT NULL, largo INT NOT NULL, ancho INT NOT NULL, coordenadas VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, juego_id INT NOT NULL, mesa_id INT NOT NULL, fecha_reserva DATETIME NOT NULL, fecha_cancelacion DATETIME DEFAULT NULL, asiste TINYINT(1) DEFAULT NULL, INDEX IDX_188D2E3BDB38439E (usuario_id), INDEX IDX_188D2E3B13375255 (juego_id), INDEX IDX_188D2E3B8BDC7AE9 (mesa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, dni VARCHAR(9) NOT NULL, nombre VARCHAR(45) NOT NULL, apellido1 VARCHAR(45) NOT NULL, apellido2 VARCHAR(45) DEFAULT NULL, email VARCHAR(60) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, telegram_id INT NOT NULL, admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_evento (usuario_id INT NOT NULL, evento_id INT NOT NULL, INDEX IDX_BD94E80CDB38439E (usuario_id), INDEX IDX_BD94E80C87A5F842 (evento_id), PRIMARY KEY(usuario_id, evento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_848A6F79AC72117 FOREIGN KEY (mesa_id_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE juego_evento ADD CONSTRAINT FK_131B1E0113375255 FOREIGN KEY (juego_id) REFERENCES juego (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE juego_evento ADD CONSTRAINT FK_131B1E0187A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B13375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B8BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE usuario_evento ADD CONSTRAINT FK_BD94E80CDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_evento ADD CONSTRAINT FK_BD94E80C87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_848A6F79AC72117');
        $this->addSql('ALTER TABLE juego_evento DROP FOREIGN KEY FK_131B1E0113375255');
        $this->addSql('ALTER TABLE juego_evento DROP FOREIGN KEY FK_131B1E0187A5F842');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BDB38439E');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B13375255');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B8BDC7AE9');
        $this->addSql('ALTER TABLE usuario_evento DROP FOREIGN KEY FK_BD94E80CDB38439E');
        $this->addSql('ALTER TABLE usuario_evento DROP FOREIGN KEY FK_BD94E80C87A5F842');
        // $this->addSql('DROP TABLE distribuicion');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE juego_evento');
        $this->addSql('DROP TABLE mesa');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_evento');
    }
}
