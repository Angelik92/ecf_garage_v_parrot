<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925121615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ads (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(100) NOT NULL, registration_nb VARCHAR(20) NOT NULL, built INT NOT NULL, kilometer INT NOT NULL, price INT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7EC9F620C3C6F69F (car_id), INDEX IDX_7EC9F620F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brands (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cars (id INT AUTO_INCREMENT NOT NULL, gearbox_id INT NOT NULL, brand_id INT NOT NULL, fuel_id INT NOT NULL, model VARCHAR(100) NOT NULL, power INT DEFAULT NULL, INDEX IDX_95C71D14C826082F (gearbox_id), INDEX IDX_95C71D1444F5D008 (brand_id), INDEX IDX_95C71D1497C79677 (fuel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fuels (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip_code INT NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gearboxes (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures (id INT AUTO_INCREMENT NOT NULL, ads_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_8F7C2FC0FE52BF81 (ads_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedules (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(50) NOT NULL, morning_schedule VARCHAR(50) DEFAULT NULL, afternoon_schedule VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimonials (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, moderator_id INT DEFAULT NULL, client VARCHAR(100) NOT NULL, date_of_service DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', rating INT NOT NULL, content LONGTEXT NOT NULL, approved TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_38311579ED5CA9E6 (service_id), INDEX IDX_38311579D0AFA354 (moderator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620C3C6F69F FOREIGN KEY (car_id) REFERENCES cars (id)');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT FK_95C71D14C826082F FOREIGN KEY (gearbox_id) REFERENCES gearboxes (id)');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT FK_95C71D1444F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
        $this->addSql('ALTER TABLE cars ADD CONSTRAINT FK_95C71D1497C79677 FOREIGN KEY (fuel_id) REFERENCES fuels (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0FE52BF81 FOREIGN KEY (ads_id) REFERENCES ads (id)');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_38311579ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_38311579D0AFA354 FOREIGN KEY (moderator_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F620C3C6F69F');
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F620F675F31B');
        $this->addSql('ALTER TABLE cars DROP FOREIGN KEY FK_95C71D14C826082F');
        $this->addSql('ALTER TABLE cars DROP FOREIGN KEY FK_95C71D1444F5D008');
        $this->addSql('ALTER TABLE cars DROP FOREIGN KEY FK_95C71D1497C79677');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0FE52BF81');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_38311579ED5CA9E6');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_38311579D0AFA354');
        $this->addSql('DROP TABLE ads');
        $this->addSql('DROP TABLE brands');
        $this->addSql('DROP TABLE cars');
        $this->addSql('DROP TABLE fuels');
        $this->addSql('DROP TABLE garages');
        $this->addSql('DROP TABLE gearboxes');
        $this->addSql('DROP TABLE pictures');
        $this->addSql('DROP TABLE schedules');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE testimonials');
        $this->addSql('DROP TABLE `user`');
    }
}
