<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403101752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD locataire_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document ADD CONSTRAINT FK_D8698A76D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D8698A76D8A38199 ON document (locataire_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP FOREIGN KEY FK_D8698A76D8A38199
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D8698A76D8A38199 ON document
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document DROP locataire_id
        SQL);
    }
}
