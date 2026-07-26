<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260726014628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating bookings table for sessions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            CREATE TABLE booking (
                id UUID NOT NULL,
                session_id UUID NOT NULL,
                user_id UUID NOT NULL,
                status VARCHAR(255) NOT NULL,
                places INT NOT NULL,
                total_price NUMERIC(10, 2) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT fk_booking_session FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE
            );'
        );
    }

    public function down(Schema $schema): void
    {

        $this->addSql('DROP TABLE booking');
    }
}
