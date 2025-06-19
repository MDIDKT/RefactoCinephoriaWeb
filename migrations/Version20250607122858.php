<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607122858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql(<<<'SQL'
            ALTER TABLE cinema CHANGE updated_at updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE film
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE salle
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE cinema CHANGE updated_at updated DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL
        );
    }
}
