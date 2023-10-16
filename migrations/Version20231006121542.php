<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231006121542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services ADD picture_file_id INT NOT NULL, DROP picture');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1698EED8DBE FOREIGN KEY (picture_file_id) REFERENCES pictures (id)');
        $this->addSql('CREATE INDEX IDX_7332E1698EED8DBE ON services (picture_file_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E1698EED8DBE');
        $this->addSql('DROP INDEX IDX_7332E1698EED8DBE ON services');
        $this->addSql('ALTER TABLE services ADD picture VARCHAR(255) NOT NULL, DROP picture_file_id');
    }
}
