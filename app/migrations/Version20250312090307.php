<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312090307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projekt_projekt DROP FOREIGN KEY FK_62A31677F3F63621');
        $this->addSql('ALTER TABLE projekt_projekt DROP FOREIGN KEY FK_62A31677EA1366AE');
        $this->addSql('DROP TABLE projekt_projekt');
        $this->addSql('ALTER TABLE projekt ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projekt ADD CONSTRAINT FK_E76A5AE6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E76A5AE6A76ED395 ON projekt (user_id)');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projekt_projekt (projekt_source INT NOT NULL, projekt_target INT NOT NULL, INDEX IDX_62A31677EA1366AE (projekt_target), INDEX IDX_62A31677F3F63621 (projekt_source), PRIMARY KEY(projekt_source, projekt_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projekt_projekt ADD CONSTRAINT FK_62A31677F3F63621 FOREIGN KEY (projekt_source) REFERENCES projekt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projekt_projekt ADD CONSTRAINT FK_62A31677EA1366AE FOREIGN KEY (projekt_target) REFERENCES projekt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projekt DROP FOREIGN KEY FK_E76A5AE6A76ED395');
        $this->addSql('DROP INDEX IDX_E76A5AE6A76ED395 ON projekt');
        $this->addSql('ALTER TABLE projekt DROP user_id');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) DEFAULT NULL');
    }
}
