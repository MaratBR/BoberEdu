<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateCoursesIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('courses', function (Mapping $mapping, Settings $settings) {
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

            $mapping->text('name', ['analyzer' => 'nameNgram']);
            $mapping->text('name_exact', ['analyzer' => 'simple']);
            $mapping->text('about');
            $mapping->text('summary');
            $mapping->text('tags');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('courses');
    }
}
