<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310133410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projekt (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projekt_projekt (projekt_source INT NOT NULL, projekt_target INT NOT NULL, INDEX IDX_62A31677F3F63621 (projekt_source), INDEX IDX_62A31677EA1366AE (projekt_target), PRIMARY KEY(projekt_source, projekt_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, projekt_id INT DEFAULT NULL, projekt_id_id INT NOT NULL, task_name VARCHAR(255) DEFAULT NULL, task_description VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_527EDB25261D545D (projekt_id), UNIQUE INDEX UNIQ_527EDB25BFF48C7C (projekt_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D6498DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projekt_projekt ADD CONSTRAINT FK_62A31677F3F63621 FOREIGN KEY (projekt_source) REFERENCES projekt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projekt_projekt ADD CONSTRAINT FK_62A31677EA1366AE FOREIGN KEY (projekt_target) REFERENCES projekt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25261D545D FOREIGN KEY (projekt_id) REFERENCES projekt (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25BFF48C7C FOREIGN KEY (projekt_id_id) REFERENCES projekt (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projekt_projekt DROP FOREIGN KEY FK_62A31677F3F63621');
        $this->addSql('ALTER TABLE projekt_projekt DROP FOREIGN KEY FK_62A31677EA1366AE');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25261D545D');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25BFF48C7C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498DB60186');
        $this->addSql('DROP TABLE projekt');
        $this->addSql('DROP TABLE projekt_projekt');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
    }
}
