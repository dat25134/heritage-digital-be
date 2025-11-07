<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Domain\ImageIntros\Models\ImageIntro;
use App\Domain\ImageIntros\Policies\ImageIntroPolicy;
use App\Domain\Topics\Models\Topic;
use App\Domain\Topics\Policies\TopicPolicy;
use App\Domain\Videos\Models\Video;
use App\Domain\Videos\Policies\VideoPolicy;
use App\Domain\Posts\Models\Post;
use App\Domain\Posts\Policies\PostPolicy;
use App\Domain\ResearchPapers\Models\ResearchPaper;
use App\Domain\ResearchPapers\Policies\ResearchPaperPolicy;
use App\Domain\Documents\Models\Document;
use App\Domain\Documents\Policies\DocumentPolicy;
use App\Domain\Books\Models\Book;
use App\Domain\Books\Policies\BookPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ImageIntro::class => ImageIntroPolicy::class,
        Topic::class => TopicPolicy::class,
        Video::class => VideoPolicy::class,
            Post::class => PostPolicy::class,
        ResearchPaper::class => ResearchPaperPolicy::class,
        Document::class => DocumentPolicy::class,
        Book::class => BookPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Allow admin to bypass all checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Define fine-grained gates mapped to spatie permissions
        $modules = [
            'images' => ['create', 'read', 'update', 'delete'],
            'image-intros' => ['create', 'read', 'update', 'delete', 'publish'],
            'topics' => ['create', 'read', 'update', 'delete', 'order'],
            'videos' => ['create', 'read', 'update', 'delete', 'upload', 'publish'],
            'posts' => ['create', 'read', 'update', 'delete', 'publish'],
            'papers' => ['create', 'read', 'update', 'delete', 'publish'],
            'backups' => ['read', 'create', 'restore', 'delete'],
            'documents' => ['create', 'read', 'update', 'delete', 'publish'],
            'books' => ['create', 'read', 'update', 'delete', 'publish', 'upload'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $ability = $module . '.' . $action;
                Gate::define($ability, function ($user) use ($ability) {
                    return $user->can($ability);
                });
            }
        }
    }
}
