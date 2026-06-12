<?php

namespace Bale\BaleOrganisasi;

use Bale\Umpak\Concerns\HasLandingPageGuard;
use Bale\Umpak\Concerns\HasLivewireComponents;
use Bale\Umpak\Umpak;
use Illuminate\Support\ServiceProvider;

class BaleOrganisasiServiceProvider extends ServiceProvider
{
    use HasLandingPageGuard, HasLivewireComponents;

    public function register(): void
    {
        $this->app->resolving(Umpak::class, function (Umpak $umpak) {
            $umpak->registerLandingPage(
                'bale-organisasi',
                \Illuminate\Support\Str::title(str_replace('-', ' ', 'bale-organisasi')),
            );
        });
    }

    protected function landingPageSlug(): string
    {
        return 'bale-organisasi';
    }

    public function boot(): void
    {
        if ($this->isActiveLandingPage()) {
            $this->app->booted(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });

            $this->app['view']->prependLocation(__DIR__.'/../resources/views');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bale-organisasi');

        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        $this->discoverLivewireComponents(
            __DIR__.'/Livewire',
            'Bale\BaleOrganisasi\Livewire',
            'bale-organisasi'
        );
    }
}