<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119105236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE page ADD article_id INT NOT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6207294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_140AB6207294869C ON page (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE titre titre VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prix prix NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6207294869C');
        $this->addSql('DROP INDEX IDX_140AB6207294869C ON page');
        $this->addSql('ALTER TABLE page DROP article_id');
    }
}
