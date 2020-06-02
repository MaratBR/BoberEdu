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
            $mapping->text('name');
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
