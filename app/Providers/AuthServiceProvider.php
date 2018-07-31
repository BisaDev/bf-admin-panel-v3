<?php

namespace Brightfox\Providers;

use Brightfox\Models\StudentExam;
use Brightfox\Policies\ShowExamResultsPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Brightfox\Model' => 'Brightfox\Policies\ModelPolicy',
        StudentExam::class => ShowExamResultsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
