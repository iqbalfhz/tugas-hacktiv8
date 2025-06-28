<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class BillingGenerator extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.billing-generator';
    //add to navigation group finance
    protected static ?string $navigationGroup = 'Finance';
    //navigation sort
    protected static ?int $navigationSort = 1;
}
