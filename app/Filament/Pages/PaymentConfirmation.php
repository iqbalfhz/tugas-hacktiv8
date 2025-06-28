<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BillingResource;
use App\Models\Billing;
use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class PaymentConfirmation extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.payment-confirmation';

    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;

    public ?Billing $record = null;
    public ?string $billingId = null;

    // This is similar to route model binding
    public function mount()
    {
        $this->billingId = request()->query('billing_id');
        if ($this->billingId) {
            try {
                // if ($billing->enrollment->student->user_id == $user->id) {
                //     return true;
                // }
                $this->record = Billing::findOrFail($this->billingId);
                //check if billing is belongs to current user
                if ($this->record->enrollment->student->user_id != Auth::user()->id) {
                    //make filamentphp notification
                    Notification::make()
                        ->title('You are not allowed to access this billing')
                        ->danger()
                        ->send();
                    $this->redirect(BillingResource::getUrl());
                }
                //check if billing is already paid
                if ($this->record->transactions()->where('status', 'paid')->exists()) {
                    //make filamentphp notification
                    Notification::make()
                        ->title('Billing already paid')
                        ->danger()
                        ->send();
                    $this->redirect(BillingResource::getUrl());
                }
                //create new Transaction using transaction model
                $transaction = Transaction::create([
                    'amount' => $this->record->amount,
                    'status' => 'pending',
                    'date' => now(),
                    'snap_token' => '',
                    'billing_id' => $this->record->id,
                ]);
                //configure midtrans library
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                // request snap token to midtrans using midtrans library
                $orderId = \Illuminate\Support\Str::uuid()->toString();
                $snapToken = \Midtrans\Snap::getSnapToken([
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $this->record->amount,
                    ],
                    'customer_details' => [
                        'first_name' => $this->record->enrollment->student->name,
                        'email' => Auth::user()->email,
                    ],
                ]);
                //update transaction with snap token
                $transaction->snap_token = $snapToken;
                $transaction->order_id = $orderId;
                $transaction->save();
            } catch (ModelNotFoundException $exception) {
                // Handle record not found, maybe redirect
                //make filamentphp notification
                Notification::make()
                    ->title('Billing not found')
                    ->danger()
                    ->send();
                $this->redirect(BillingResource::getUrl());
            }
        }
    }
}
