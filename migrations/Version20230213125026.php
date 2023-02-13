<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213125026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE evento ADD tramo_id INT NOT NULL');
        // $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B056E801575 FOREIGN KEY (tramo_id) REFERENCES tramos (id)');
        // $this->addSql('CREATE INDEX IDX_47860B056E801575 ON evento (tramo_id)');
        // $this->addSql('ALTER TABLE reserva ADD tramo_id INT NOT NULL');
        // $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B6E801575 FOREIGN KEY (tramo_id) REFERENCES tramos (id)');
        // $this->addSql('CREATE INDEX IDX_188D2E3B6E801575 ON reserva (tramo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B056E801575');
        $this->addSql('DROP INDEX IDX_47860B056E801575 ON evento');
        $this->addSql('ALTER TABLE evento DROP tramo_id');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B6E801575');
        $this->addSql('DROP INDEX IDX_188D2E3B6E801575 ON reserva');
        $this->addSql('ALTER TABLE reserva DROP tramo_id');
    }
}
