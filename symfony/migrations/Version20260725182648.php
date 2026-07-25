<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260725182648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating experience';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            CREATE TABLE experience (
                id UUID NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                provider_id UUID NOT NULL,
                PRIMARY KEY(id)
            );'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE experience');
    }
}
