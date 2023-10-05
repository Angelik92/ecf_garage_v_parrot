<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003073740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ads ADD update_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620CA83C286 FOREIGN KEY (update_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_7EC9F620CA83C286 ON ads (update_by_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EA244345E237E06 ON brands (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95C71D14D79572D9 ON cars (model)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F620CA83C286');
        $this->addSql('DROP INDEX IDX_7EC9F620CA83C286 ON ads');
        $this->addSql('ALTER TABLE ads DROP update_by_id');
        $this->addSql('DROP INDEX UNIQ_95C71D14D79572D9 ON cars');
        $this->addSql('DROP INDEX UNIQ_7EA244345E237E06 ON brands');
    }
}
