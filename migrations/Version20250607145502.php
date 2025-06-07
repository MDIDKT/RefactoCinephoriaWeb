<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250607145502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, sujet VARCHAR(255) DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE incident (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', description LONGTEXT NOT NULL, status LONGTEXT NOT NULL COMMENT '(DC2Type:simple_array)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_3D03A11A8C03F15C (employee_id), INDEX IDX_3D03A11ADC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A8C03F15C FOREIGN KEY (employee_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident ADD CONSTRAINT FK_3D03A11ADC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD role LONGTEXT NOT NULL COMMENT '(DC2Type:simple_array)', DROP roles
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A8C03F15C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11ADC304035
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE incident
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD roles VARCHAR(255) NOT NULL, DROP role
        SQL);
    }
}
