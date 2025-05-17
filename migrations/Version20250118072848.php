<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118072848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, film_id INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, note INT NOT NULL, approuve TINYINT(1) NOT NULL, INDEX IDX_8F91ABF0567F5183 (film_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cinemas (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, horaire VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE films (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, age_minimum INT NOT NULL, coup_de_coeur TINYINT(1) NOT NULL, note DOUBLE PRECISION NOT NULL, qualite VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE films_cinemas (films_id INT NOT NULL, cinemas_id INT NOT NULL, INDEX IDX_65DC6439939610EE (films_id), INDEX IDX_65DC6439C5C76018 (cinemas_id), PRIMARY KEY(films_id, cinemas_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incidents (id INT AUTO_INCREMENT NOT NULL, salle_id INT NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_E65135D0DC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, cinemas_id INT DEFAULT NULL, seances_id INT DEFAULT NULL, films_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nombre_places INT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, status VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_4DA239C5C76018 (cinemas_id), INDEX IDX_4DA23910F09302 (seances_id), INDEX IDX_4DA239939610EE (films_id), INDEX IDX_4DA239DC304035 (salle_id), INDEX IDX_4DA239A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salles (id INT AUTO_INCREMENT NOT NULL, nombre_places INT NOT NULL, nombre_siege INT NOT NULL, nombre_siege_pmr INT NOT NULL, numero_salle INT NOT NULL, nombre_places_disponibles INT NOT NULL, places_occupees INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, films_id INT DEFAULT NULL, salle_id INT DEFAULT NULL, cinemas_id INT DEFAULT NULL, heure_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', heure_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nombre_places INT DEFAULT NULL, qualite VARCHAR(255) NOT NULL, INDEX IDX_DF7DFD0E939610EE (films_id), INDEX IDX_DF7DFD0EDC304035 (salle_id), INDEX IDX_DF7DFD0EC5C76018 (cinemas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sieges (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, numero_siege INT NOT NULL, siege_pmr TINYINT(1) NOT NULL, INDEX IDX_38B0AE00B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(180) NOT NULL, prenom VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D6496C6E55B5 (nom), UNIQUE INDEX UNIQ_8D93D649A625945B (prenom), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0567F5183 FOREIGN KEY (film_id) REFERENCES films (id)');
        $this->addSql('ALTER TABLE films_cinemas ADD CONSTRAINT FK_65DC6439939610EE FOREIGN KEY (films_id) REFERENCES films (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE films_cinemas ADD CONSTRAINT FK_65DC6439C5C76018 FOREIGN KEY (cinemas_id) REFERENCES cinemas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0DC304035 FOREIGN KEY (salle_id) REFERENCES salles (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239C5C76018 FOREIGN KEY (cinemas_id) REFERENCES cinemas (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23910F09302 FOREIGN KEY (seances_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239939610EE FOREIGN KEY (films_id) REFERENCES films (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239DC304035 FOREIGN KEY (salle_id) REFERENCES salles (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E939610EE FOREIGN KEY (films_id) REFERENCES films (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EDC304035 FOREIGN KEY (salle_id) REFERENCES salles (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EC5C76018 FOREIGN KEY (cinemas_id) REFERENCES cinemas (id)');
        $this->addSql('ALTER TABLE sieges ADD CONSTRAINT FK_38B0AE00B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0567F5183');
        $this->addSql('ALTER TABLE films_cinemas DROP FOREIGN KEY FK_65DC6439939610EE');
        $this->addSql('ALTER TABLE films_cinemas DROP FOREIGN KEY FK_65DC6439C5C76018');
        $this->addSql('ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0DC304035');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239C5C76018');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23910F09302');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239939610EE');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239DC304035');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239A76ED395');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E939610EE');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC304035');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EC5C76018');
        $this->addSql('ALTER TABLE sieges DROP FOREIGN KEY FK_38B0AE00B83297E7');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cinemas');
        $this->addSql('DROP TABLE films');
        $this->addSql('DROP TABLE films_cinemas');
        $this->addSql('DROP TABLE incidents');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE salles');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE sieges');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
