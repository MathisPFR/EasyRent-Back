<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402101538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE bien ADD users_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bien ADD CONSTRAINT FK_45EDC38667B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_45EDC38667B3B43D ON bien (users_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE bien DROP FOREIGN KEY FK_45EDC38667B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_45EDC38667B3B43D ON bien
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE bien DROP users_id
        SQL);
    }
}
