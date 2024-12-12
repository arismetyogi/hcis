<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect('/admin');
});

Route::get('/admin/my-profile', \Jeffgreco13\FilamentBreezy\Pages\MyProfilePage::class)
    ->middleware(['auth', 'verified'])
    ->name('filament.admin.pages.my-profile');