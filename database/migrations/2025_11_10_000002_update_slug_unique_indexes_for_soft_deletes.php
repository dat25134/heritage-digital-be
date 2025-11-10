<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Books: slug, isbn
        if (DB::getDriverName() === 'mysql') {
            // Books
            $this->dropIndexIfExists('books', 'books_slug_unique');
            $this->dropIndexIfExists('books', 'books_isbn_unique');
            DB::statement("ALTER TABLE `books` ADD UNIQUE `books_slug_deleted_at_unique` (`slug`, `deleted_at`)");
            DB::statement("ALTER TABLE `books` ADD UNIQUE `books_isbn_deleted_at_unique` (`isbn`, `deleted_at`)");

            // Posts
            $this->dropIndexIfExists('posts', 'posts_slug_unique');
            DB::statement("ALTER TABLE `posts` ADD UNIQUE `posts_slug_deleted_at_unique` (`slug`, `deleted_at`)");

            // Research papers: slug, doi
            $this->dropIndexIfExists('research_papers', 'research_papers_slug_unique');
            $this->dropIndexIfExists('research_papers', 'research_papers_doi_unique');
            DB::statement("ALTER TABLE `research_papers` ADD UNIQUE `research_papers_slug_deleted_at_unique` (`slug`, `deleted_at`)");
            DB::statement("ALTER TABLE `research_papers` ADD UNIQUE `research_papers_doi_deleted_at_unique` (`doi`, `deleted_at`)");

            // Documents
            $this->dropIndexIfExists('documents', 'documents_slug_unique');
            DB::statement("ALTER TABLE `documents` ADD UNIQUE `documents_slug_deleted_at_unique` (`slug`, `deleted_at`)");

            // Topics
            $this->dropIndexIfExists('topics', 'topics_slug_unique');
            DB::statement("ALTER TABLE `topics` ADD UNIQUE `topics_slug_deleted_at_unique` (`slug`, `deleted_at`)");

            // Image intros
            $this->dropIndexIfExists('image_intros', 'image_intros_slug_unique');
            DB::statement("ALTER TABLE `image_intros` ADD UNIQUE `image_intros_slug_deleted_at_unique` (`slug`, `deleted_at`)");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Books
            $this->dropIndexIfExists('books', 'books_slug_deleted_at_unique');
            $this->dropIndexIfExists('books', 'books_isbn_deleted_at_unique');
            DB::statement("ALTER TABLE `books` ADD UNIQUE `books_slug_unique` (`slug`)");
            DB::statement("ALTER TABLE `books` ADD UNIQUE `books_isbn_unique` (`isbn`)");

            // Posts
            $this->dropIndexIfExists('posts', 'posts_slug_deleted_at_unique');
            DB::statement("ALTER TABLE `posts` ADD UNIQUE `posts_slug_unique` (`slug`)");

            // Research papers
            $this->dropIndexIfExists('research_papers', 'research_papers_slug_deleted_at_unique');
            $this->dropIndexIfExists('research_papers', 'research_papers_doi_deleted_at_unique');
            DB::statement("ALTER TABLE `research_papers` ADD UNIQUE `research_papers_slug_unique` (`slug`)");
            DB::statement("ALTER TABLE `research_papers` ADD UNIQUE `research_papers_doi_unique` (`doi`)");

            // Documents
            $this->dropIndexIfExists('documents', 'documents_slug_deleted_at_unique');
            DB::statement("ALTER TABLE `documents` ADD UNIQUE `documents_slug_unique` (`slug`)");

            // Topics
            $this->dropIndexIfExists('topics', 'topics_slug_deleted_at_unique');
            DB::statement("ALTER TABLE `topics` ADD UNIQUE `topics_slug_unique` (`slug`)");

            // Image intros
            $this->dropIndexIfExists('image_intros', 'image_intros_slug_deleted_at_unique');
            DB::statement("ALTER TABLE `image_intros` ADD UNIQUE `image_intros_slug_unique` (`slug`)");
        }
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        try {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
        } catch (\Throwable $e) {
            // index might not exist; ignore
        }
    }
};

