<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119155314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6207974B702');
        $this->addSql('DROP INDEX IDX_140AB6207974B702 ON page');
        $this->addSql('ALTER TABLE page DROP page_article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD page_article_id INT NOT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6207974B702 FOREIGN KEY (page_article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_140AB6207974B702 ON page (page_article_id)');
    }
}
