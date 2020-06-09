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
            $settings->analysis([
                "analyzer" => [
                    "nameNgram" => [
                        "type" => "custom",
                        "filter" => "lowercase",
                        "tokenizer" => "customNgram"
                    ]
                ],
                "tokenizer" => [
                    "customNgram" => [
                        "type" => "nGram",
                        "min_gram" => "1",
                        "max_gram" => "2"
                    ]
                ]
            ]);

            $mapping->keyword('email');
            $mapping->searchAsYouType('name', ['analyzer' => 'nameNgram']);
            $mapping->searchAsYouType('display_name', ['analyzer' => 'nameNgram']);
            $mapping->text('about');
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
