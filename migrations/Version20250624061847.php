<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624061847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE siege (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, numero INT NOT NULL, pmr TINYINT(1) DEFAULT NULL, INDEX IDX_6706B4F7B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE siege ADD CONSTRAINT FK_6706B4F7B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE siege DROP FOREIGN KEY FK_6706B4F7B83297E7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE siege
        SQL);
    }
}
