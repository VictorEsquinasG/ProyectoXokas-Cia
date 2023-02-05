<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202153117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BDB38439E');
        // $this->addSql('CREATE TABLE fechas_festivos (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE tramos (id INT AUTO_INCREMENT NOT NULL, hora_inicio TIME NOT NULL, hora_fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE usuario_evento DROP FOREIGN KEY FK_BD94E80C87A5F842');
        // $this->addSql('ALTER TABLE usuario_evento DROP FOREIGN KEY FK_BD94E80CDB38439E');
        // $this->addSql('DROP TABLE usuario');
        // $this->addSql('DROP TABLE usuario_evento');
        // $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_698658A79AC72117 FOREIGN KEY (mesa_id_id) REFERENCES mesa (id)');
        // $this->addSql('DROP INDEX idx_848a6f79ac72117 ON distribucion');
        // $this->addSql('CREATE INDEX IDX_698658A79AC72117 ON distribucion (mesa_id_id)');
        $this->addSql('ALTER TABLE juego ADD imagen LONGBLOB NOT NULL');
        $this->addSql('DROP INDEX IDX_188D2E3BDB38439E ON reserva');
        $this->addSql('ALTER TABLE reserva DROP usuario_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, dni VARCHAR(9) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nombre VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, apellido1 VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, apellido2 VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telefono VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, telegram_id INT NOT NULL, admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE usuario_evento (usuario_id INT NOT NULL, evento_id INT NOT NULL, INDEX IDX_BD94E80C87A5F842 (evento_id), INDEX IDX_BD94E80CDB38439E (usuario_id), PRIMARY KEY(usuario_id, evento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE usuario_evento ADD CONSTRAINT FK_BD94E80C87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_evento ADD CONSTRAINT FK_BD94E80CDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE fechas_festivos');
        $this->addSql('DROP TABLE tramos');
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_698658A79AC72117');
        $this->addSql('ALTER TABLE distribucion DROP FOREIGN KEY FK_698658A79AC72117');
        $this->addSql('DROP INDEX idx_698658a79ac72117 ON distribucion');
        $this->addSql('CREATE INDEX IDX_848A6F79AC72117 ON distribucion (mesa_id_id)');
        $this->addSql('ALTER TABLE distribucion ADD CONSTRAINT FK_698658A79AC72117 FOREIGN KEY (mesa_id_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE juego DROP imagen');
        $this->addSql('ALTER TABLE reserva ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BDB38439E ON reserva (usuario_id)');
    }
}
