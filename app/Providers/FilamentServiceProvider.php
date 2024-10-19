<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // Filament::serving(function () {
    //   $user = auth()->user();
    //   $departmentName = $user ? $user->department->name : 'No Department'; // Get the department name

    //   Filament::registerNavigationItems(
    //     [
    //       'label' => $departmentName,
    //       'url' => '/admin', // Or specify a URL
    //       'icon' => 'heroicon-o-office-building', // Change to an appropriate icon
    //     ],
    //   );
    // });
  }
}
