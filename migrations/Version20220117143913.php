<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220117143913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_auteur (article_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_6F9D26C07294869C (article_id), INDEX IDX_6F9D26C060BB6FE6 (auteur_id), PRIMARY KEY(article_id, auteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_personnage (article_id INT NOT NULL, personnage_id INT NOT NULL, INDEX IDX_563399EC7294869C (article_id), INDEX IDX_563399EC5E315342 (personnage_id), PRIMARY KEY(article_id, personnage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_article (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_personnage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(45) NOT NULL, presentation LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legende (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(45) NOT NULL, resume LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, page_article_id INT NOT NULL, numero INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_140AB6207974B702 (page_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_auteur ADD CONSTRAINT FK_6F9D26C07294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_auteur ADD CONSTRAINT FK_6F9D26C060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_personnage ADD CONSTRAINT FK_563399EC7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_personnage ADD CONSTRAINT FK_563399EC5E315342 FOREIGN KEY (personnage_id) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6207974B702 FOREIGN KEY (page_article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article ADD categorie_id INT NOT NULL, ADD admin_id INT NOT NULL, ADD resume LONGTEXT NOT NULL, DROP type, CHANGE titre titre VARCHAR(45) NOT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_article (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66BCF5E72D ON article (categorie_id)');
        $this->addSql('CREATE INDEX IDX_23A0E66642B8210 ON article (admin_id)');
        $this->addSql('ALTER TABLE auteur ADD nom VARCHAR(45) NOT NULL, ADD presentation LONGTEXT NOT NULL, CHANGE name image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE personnage ADD categorie_id INT NOT NULL, ADD presentation LONGTEXT NOT NULL, CHANGE nom nom VARCHAR(45) NOT NULL, CHANGE appartenance image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_personnage (id)');
        $this->addSql('CREATE INDEX IDX_6AEA486DBCF5E72D ON personnage (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486DBCF5E72D');
        $this->addSql('DROP TABLE article_auteur');
        $this->addSql('DROP TABLE article_personnage');
        $this->addSql('DROP TABLE categorie_article');
        $this->addSql('DROP TABLE categorie_personnage');
        $this->addSql('DROP TABLE legende');
        $this->addSql('DROP TABLE page');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66642B8210');
        $this->addSql('DROP INDEX IDX_23A0E66BCF5E72D ON article');
        $this->addSql('DROP INDEX IDX_23A0E66642B8210 ON article');
        $this->addSql('ALTER TABLE article ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP categorie_id, DROP admin_id, DROP resume, CHANGE titre titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prix prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE auteur DROP nom, DROP presentation, CHANGE image name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_6AEA486DBCF5E72D ON personnage');
        $this->addSql('ALTER TABLE personnage DROP categorie_id, DROP presentation, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image appartenance VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
