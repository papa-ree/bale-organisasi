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
                'organisasi',
                \Illuminate\Support\Str::title(str_replace('-', ' ', 'organisasi')),
            );
        });
    }

    protected function landingPageSlug(): string
    {
        return 'organisasi';
    }

    public function boot(): void
    {
        if ($this->isActiveLandingPage()) {
            $this->app->booted(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

                // Daftarkan LandingPageComposer hanya untuk views package ini.
                // Tanpa guard ini, composer akan menjalankan DB query pada semua
                // bale lain yang berjalan di server yang sama.
                \Bale\Umpak\UmpakServiceProvider::registerLandingPageComposer('bale-organisasi::*');
            });

            $this->app['view']->prependLocation(__DIR__ . '/../resources/views');

            // Guard: Livewire component hanya didaftarkan saat bale ini aktif.
            // Tanpa ini, class reference tersimpan di shared Redis dan crash
            // di project production yang tidak memiliki class tersebut.
            $this->registerLivewireComponents();
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bale-organisasi');
    }


    protected function registerLivewireComponents(): void
    {
        $this->discoverLivewireComponents(
            __DIR__ . '/Livewire',
            'Bale\BaleOrganisasi\Livewire',
            'bale-organisasi'
        );
    }
}