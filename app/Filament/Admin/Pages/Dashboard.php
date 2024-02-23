<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use JibayMcs\FilamentTour\Tour\Tour;
use JibayMcs\FilamentTour\Tour\HasTour;
use JibayMcs\FilamentTour\Tour\Step;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasTour;
    public function tours(): array {
        return [
        ];
    }


}
