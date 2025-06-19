<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614123105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD cinema_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD CONSTRAINT FK_4E977E5CB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E977E5CB4CB84B6 ON salle (cinema_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE salle DROP FOREIGN KEY FK_4E977E5CB4CB84B6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_4E977E5CB4CB84B6 ON salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle DROP cinema_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
    }
}
