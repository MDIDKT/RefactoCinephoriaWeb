<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607130244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, film_id INT DEFAULT NULL, note INT NOT NULL, commentaire LONGTEXT NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_8F91ABF0A76ED395 (user_id), INDEX IDX_8F91ABF0567F5183 (film_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, cinema_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, film_id INT DEFAULT NULL, heure_debut DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', heure_fin DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_DF7DFD0EB4CB84B6 (cinema_id), INDEX IDX_DF7DFD0EDC304035 (salle_id), INDEX IDX_DF7DFD0E567F5183 (film_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0567F5183 FOREIGN KEY (film_id) REFERENCES film (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E567F5183 FOREIGN KEY (film_id) REFERENCES film (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0567F5183
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EB4CB84B6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC304035
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E567F5183
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE avis
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE seance
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL
        SQL);
    }
}
