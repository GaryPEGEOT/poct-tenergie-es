<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * @see self::getDescription()
 */
final class Version20211001080046 extends AbstractMigration
{
    private const TABLE_NAME = 'todo_item';

    public function getDescription(): string
    {
        return 'Create TodoItem table';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf($schema->hasTable(self::TABLE_NAME), 'Table already exists');

        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', Types::INTEGER)->setAutoincrement(true);
        $table->addColumn('title', Types::STRING)->setLength(80);
        $table->addColumn('description', Types::TEXT)->setNotnull(false);
        $table->addColumn('is_done', Types::BOOLEAN);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE);

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $this->skipIf(!$schema->hasTable(self::TABLE_NAME), 'Table not found');

        $schema->dropTable(self::TABLE_NAME);
    }
}
