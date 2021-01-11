<?php

namespace TheSoftwareFarm\MicrosoftTeams;

use Illuminate\Support\ServiceProvider;

class MicrosoftTeamsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/microsoft_teams.php' => config_path('microsoft_teams.php')
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('microsoftteams', static fn () => new MicrosoftTeams());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'microsoftteams',
        ];
    }
}
