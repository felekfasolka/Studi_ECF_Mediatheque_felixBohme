<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007150507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD is_borrowed_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3311FCDE86F FOREIGN KEY (is_borrowed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A3311FCDE86F ON book (is_borrowed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3311FCDE86F');
        $this->addSql('DROP INDEX IDX_CBE5A3311FCDE86F ON book');
        $this->addSql('ALTER TABLE book DROP is_borrowed_by_id');
    }
}
