<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BillingResource;
use App\Models\Billing;
use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentSuccess extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.payment-success';

    //hide from navigation
    protected static bool $shouldRegisterNavigation = false;

    public ?Transaction $transaction = null;
    public ?Billing $billing = null;

    public function mount() {}
}
