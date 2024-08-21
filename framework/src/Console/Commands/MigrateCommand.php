<?php

namespace Gmo\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Gmo\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    // docker-compose exec php php /var/www/html/console migrate
    private string $name = 'migrate';

    private const MIGRATION_TABLE = 'migrations';

    public function __construct(private Connection $connection, private string $migrationsPath) {}

    public function execute(array $params = []): int
    {

        try {
            $this->createMigrationsTable();

            $this->connection->beginTransaction();

            $this->connection->commit();

            $appliedMigrations = $this->getAppliedMigrations();
            $migrationsFiles = $this->getMigrationsFiles();

            $migrationsToApply = array_values(array_diff($migrationsFiles, $appliedMigrations));
            dd($migrationsToApply);
            if (! empty($migrationsToApply)) {
                $this->applyMigrations($migrationsToApply);
            }

            return 0;

        } catch (Exception $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist([self::MIGRATION_TABLE])) {
            echo 'Migration table already exists' . PHP_EOL;

            return;
        }

        $schema = new Schema;

        $table = $schema->createTable(self::MIGRATION_TABLE);
        $table->addColumn(
            'id',
            TYPES::INTEGER,
            ['autoincrement' => true, 'unsigned' => true]
        );
        $table->addColumn('migration', TYPES::STRING, ['length' => 255]);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);

        $sqlArr = $schema->toSql($this->connection->getDatabasePlatform());
        $this->connection->executeQuery($sqlArr[0]);

        echo 'Migration table created' . PHP_EOL;
    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('migration')
            ->from(self::MIGRATION_TABLE)
            ->orderBy('id', 'ASC');
        $migrations = $queryBuilder->executeQuery()->fetchAllAssociative();

        return array_column($migrations, 'migration');
    }

    /**
     * Returns an array of migration files from the given path.
     *
     * @return array<string> The list of migration files.
     */
    private function getMigrationsFiles(): array
    {

        $migrationsPath = $this->migrationsPath;
        $migrationsFiles = scandir($migrationsPath);

        $files = array_filter($migrationsFiles, function (string $file): bool {
            return str_ends_with($file, '.php');
        });

        return array_values($files);
    }
}
