<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250305084232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration für die Task-Entität mit zwei Projekt-Relationen (ManyToOne und OneToOne).';
    }

    public function up(Schema $schema): void
    {
        // 1) Tabelle "task" anlegen
        //    - "projekt_id" (ManyToOne) ist nullable
        //    - "projekt_id_id" (OneToOne) ist NICHT NULL
        $this->addSql('
            CREATE TABLE task (
                id INT AUTO_INCREMENT NOT NULL,
                task_name VARCHAR(255) DEFAULT NULL,
                task_description VARCHAR(255) DEFAULT NULL,
                status VARCHAR(255) NOT NULL,
                projekt_id INT DEFAULT NULL,
                projekt_id_id INT NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                INDEX IDX_TASK_PROJEKT (projekt_id),
                UNIQUE INDEX UNIQ_TASK_PROJEKT_ID_ID (projekt_id_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4
              COLLATE `utf8mb4_unicode_ci`
              ENGINE = InnoDB
        ');

        // 2) Fremdschlüssel für ManyToOne (projekt_id -> projekt.id)
        //    Falls du bei Löschung des Projekts Tasks ebenfalls löschen möchtest,
        //    könntest du CASCADE verwenden. Hier im Beispiel nur das Standardverhalten:
        $this->addSql('
            ALTER TABLE task 
                ADD CONSTRAINT FK_TASK_PROJEKT
                FOREIGN KEY (projekt_id) 
                REFERENCES projekt (id)
                ON DELETE SET NULL
        ');

        // 3) Fremdschlüssel für OneToOne (projekt_id_id -> projekt.id)
        $this->addSql('
            ALTER TABLE task 
                ADD CONSTRAINT FK_TASK_PROJEKT_ID_ID
                FOREIGN KEY (projekt_id_id)
                REFERENCES projekt (id)
        ');
    }

    public function down(Schema $schema): void
    {
        // Tabelle wieder entfernen
        $this->addSql('DROP TABLE task');
    }
}
