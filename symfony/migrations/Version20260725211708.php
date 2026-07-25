<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260725211708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating session table for experiences';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            CREATE TABLE session (
                id UUID NOT NULL,
                experience_id UUID NOT NULL,
                session_date TIMESTAMP NOT NULL,
                max_capacity INT NOT NULL,
                price FLOAT NOT NULL,
                reserved_places INT NOT NULL DEFAULT 0,
                PRIMARY KEY(id),
                CONSTRAINT fk_session_experience FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE
            );'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE session');
    }
}
