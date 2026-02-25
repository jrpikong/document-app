<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class WpInspectCommand extends Command
{
    protected $signature = 'wp:inspect
        {--connection=wordpress : Database connection name}
        {--table=* : Exact table(s) to inspect}
        {--like=* : SQL-like pattern(s), example: wp20_wpdatatable%}
        {--sample=25 : Number of sample rows per table}
        {--schema-only : Skip sample rows}
        {--output= : Optional JSON output file path}';

    protected $description = 'Inspect WordPress tables, schema, and sample data for migration mapping.';

    public function handle(): int
    {
        $connectionName = (string) $this->option('connection');
        $sampleSize = max(0, (int) $this->option('sample'));
        $schemaOnly = (bool) $this->option('schema-only');
        $outputPath = $this->resolveOutputPath($this->option('output'));

        try {
            $connection = DB::connection($connectionName);
            $connection->getPdo();
        } catch (Throwable $e) {
            $this->error("Cannot connect to [{$connectionName}]: {$e->getMessage()}");
            return self::FAILURE;
        }

        $tables = $this->listTables($connection);
        if ($tables === []) {
            $this->warn('No tables found on this connection.');
            return self::SUCCESS;
        }

        $selectedTables = $this->filterTables($tables);
        if ($selectedTables === []) {
            $this->warn('No tables matched filter. Use --table or --like options.');
            return self::SUCCESS;
        }

        $this->info(sprintf('Inspecting %d table(s) on connection [%s]...', count($selectedTables), $connectionName));

        $report = [
            'connection' => $connectionName,
            'generated_at' => now()->toDateTimeString(),
            'filters' => [
                'table' => $this->option('table'),
                'like' => $this->option('like'),
                'sample' => $sampleSize,
                'schema_only' => $schemaOnly,
            ],
            'tables' => [],
            'errors' => [],
        ];

        foreach ($selectedTables as $tableName) {
            $this->line(" - {$tableName}");

            try {
                $tableReport = $this->inspectTable($connection, $tableName, $sampleSize, $schemaOnly);
                $report['tables'][$tableName] = $tableReport;
            } catch (Throwable $e) {
                $report['errors'][] = [
                    'table' => $tableName,
                    'message' => $e->getMessage(),
                ];
            }
        }

        $this->writeJsonReport($outputPath, $report);
        $this->newLine();
        $this->info("Report saved to: {$outputPath}");
        $this->line(sprintf(
            'Tables inspected: %d, errors: %d',
            count($report['tables']),
            count($report['errors'])
        ));

        if (! empty($report['errors'])) {
            $this->warn('Some tables failed to inspect. Check the JSON report for details.');
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function listTables(ConnectionInterface $connection): array
    {
        $rows = $connection->select('SHOW TABLES');
        if ($rows === []) {
            return [];
        }

        $firstRow = (array) $rows[0];
        $columnKey = array_key_first($firstRow);

        return array_values(array_map(
            static fn ($row) => (string) ((array) $row)[$columnKey],
            $rows
        ));
    }

    /**
     * @param array<int, string> $tables
     * @return array<int, string>
     */
    private function filterTables(array $tables): array
    {
        $exact = array_values(array_filter(array_map('strval', Arr::wrap($this->option('table')))));
        if ($exact !== []) {
            return array_values(array_filter($tables, static fn (string $table) => in_array($table, $exact, true)));
        }

        $patterns = array_values(array_filter(array_map('strval', Arr::wrap($this->option('like')))));
        if ($patterns === []) {
            $patterns = [
                '%wpdatatable%',
                '%datatable%',
                '%users%',
                '%usermeta%',
            ];
        }

        return array_values(array_filter($tables, function (string $table) use ($patterns): bool {
            foreach ($patterns as $pattern) {
                if ($this->matchesLikePattern($table, $pattern)) {
                    return true;
                }
            }

            return false;
        }));
    }

    private function matchesLikePattern(string $value, string $pattern): bool
    {
        $escaped = preg_quote($pattern, '/');
        $regex = '/^' . str_replace(['%', '_'], ['.*', '.'], $escaped) . '$/i';

        return (bool) preg_match($regex, $value);
    }

    /**
     * @return array<string, mixed>
     */
    private function inspectTable(
        ConnectionInterface $connection,
        string $tableName,
        int $sampleSize,
        bool $schemaOnly
    ): array {
        $quoted = $this->quoteIdentifier($tableName);
        $createRow = (array) $connection->selectOne("SHOW CREATE TABLE {$quoted}");
        $createSql = $this->extractCreateSql($createRow);

        $columns = array_map(static function ($row): array {
            $item = (array) $row;
            return [
                'field' => $item['Field'] ?? null,
                'type' => $item['Type'] ?? null,
                'null' => $item['Null'] ?? null,
                'key' => $item['Key'] ?? null,
                'default' => $item['Default'] ?? null,
                'extra' => $item['Extra'] ?? null,
            ];
        }, $connection->select("SHOW COLUMNS FROM {$quoted}"));

        $columnNames = array_values(array_filter(array_map(
            static fn (array $col) => is_string($col['field']) ? $col['field'] : null,
            $columns
        )));

        $samples = [];
        if (! $schemaOnly && $sampleSize > 0) {
            $query = $connection->table($tableName);
            foreach (['id', 'ID', 'created_at', 'post_date'] as $candidate) {
                if (in_array($candidate, $columnNames, true)) {
                    $query->orderBy($candidate);
                    break;
                }
            }

            $samples = $query->limit($sampleSize)->get()->map(static fn ($row) => (array) $row)->all();
        }

        return [
            'create_table_sql' => $createSql,
            'columns' => $columns,
            'column_hints' => $this->buildColumnHints($columnNames),
            'samples' => $samples,
        ];
    }

    /**
     * @param array<string, mixed> $row
     */
    private function extractCreateSql(array $row): string
    {
        foreach ($row as $key => $value) {
            if (stripos((string) $key, 'create table') !== false) {
                return (string) $value;
            }
        }

        return (string) end($row);
    }

    /**
     * @param array<int, string> $columnNames
     * @return array<string, array<int, string>>
     */
    private function buildColumnHints(array $columnNames): array
    {
        $map = [
            'title' => ['title', 'judul', 'name', 'document'],
            'file' => ['file', 'url', 'path', 'attachment', 'lampiran'],
            'category' => ['category', 'kategori'],
            'status' => ['status', 'state'],
            'uploader' => ['upload', 'uploader', 'author', 'created_by', 'user'],
            'approver' => ['approve', 'approver', 'reviewed_by'],
            'submitted_at' => ['submitted_at', 'submit_date', 'sent_at'],
            'approved_at' => ['approved_at', 'approve_date', 'reviewed_at'],
            'reject_note' => ['reject', 'note', 'reason', 'comment'],
        ];

        $result = [];
        foreach ($map as $label => $keywords) {
            $result[$label] = array_values(array_filter($columnNames, static function (string $column) use ($keywords): bool {
                $lower = strtolower($column);
                foreach ($keywords as $keyword) {
                    if (str_contains($lower, $keyword)) {
                        return true;
                    }
                }

                return false;
            }));
        }

        return $result;
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    private function resolveOutputPath(mixed $option): string
    {
        $option = is_string($option) ? trim($option) : '';
        if ($option !== '') {
            return $option;
        }

        return storage_path('app/wp-inspection/wp-inspection-' . now()->format('Ymd-His') . '.json');
    }

    /**
     * @param array<string, mixed> $report
     */
    private function writeJsonReport(string $outputPath, array $report): void
    {
        $directory = dirname($outputPath);
        if (! is_dir($directory)) {
            File::ensureDirectoryExists($directory);
        }

        File::put(
            $outputPath,
            json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
