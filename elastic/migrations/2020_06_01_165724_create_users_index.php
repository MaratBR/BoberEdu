<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateUsersIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('users', function (Mapping $mapping, Settings $settings) {
            $mapping->keyword('email');
            $mapping->text('name', ['analyzer' => 'username_analyzer']);
            $mapping->text('display_name', ['analyzer' => 'username_analyzer']);
            $mapping->text('about');

            $settings->analysis([
                'analyzer' => [
                    'username_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'username_tokenizer',
                        "filter" => ["lowercase"],
                    ]
                ],
                'tokenizer' => [
                    'username_tokenizer' => [
                        "type" => "simple_pattern",
                        "pattern" => ".{1,2}"
                    ]
                ]
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('users');
    }
}
