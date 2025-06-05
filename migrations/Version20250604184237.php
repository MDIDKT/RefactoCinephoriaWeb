<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604184237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0DC304035
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239C5C76018
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23910F09302
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239939610EE
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239DC304035
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E939610EE
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC304035
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EC5C76018
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE cinemas
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE films
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE films_cinemas
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE incidents
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE reservations
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE salles
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE seance
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL
        );
    }
}
