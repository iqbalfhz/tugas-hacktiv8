<x-filament-panels::page>
    @if ($record)
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 bg-primary-600">
                <h2 class="text-2xl font-bold text-white">Payment Details</h2>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Information -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium">Student Information</h3>
                        <div class="border-t border-gray-200 pt-3">
                            <p><span class="font-medium">Name:</span> {{ $record->enrollment->student->name }}</p>
                            <p><span class="font-medium">Class:</span> {{ $record->enrollment->classroom->name }} -
                                {{ $record->enrollment->classroom->level->name }}</p>
                            <p><span class="font-medium">Academic Year:</span>
                                {{ $record->enrollment->classroom->academicYear->year }}</p>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium">Billing Information</h3>
                        <div class="border-t border-gray-200 pt-3">
                            <p><span class="font-medium">ID:</span> #{{ $record->id }}</p>
                            <p><span class="font-medium">Date:</span> {{ $record->date->format('d M Y') }}</p>
                            <p><span class="font-medium">Status:</span>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $record->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </p>
                            <p><span class="font-medium">Amount:</span> Rp
                                {{ number_format($record->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                @if ($record->note)
                    <div class="space-y-3 mt-6">
                        <h3 class="text-lg font-medium">Note</h3>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="prose max-w-none">
                                {!! $record->note !!}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Button -->
                @if ($record->status != 'paid')
                    <div class="mt-8 flex justify-center">
                        <button id="pay-button"
                            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm transition">
                            Pay Now
                        </button>
                    </div>

                    <!-- Midtrans Integration -->
                    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
                    </script>
                    <script>
                        document.getElementById('pay-button').onclick = function() {
                            // Get the latest transaction with the snap token
                            const snapToken = "{{ $record->transactions->last()->snap_token ?? '' }}";

                            if (!snapToken) {
                                alert('Payment token not found. Please try again later.');
                                return;
                            }

                            snap.pay(snapToken, {
                                onSuccess: function(result) {
                                    window.location.href = "{{ route('filament.admin.pages.payment-success') }}";
                                },
                                onPending: function(result) {
                                    window.location.href = "{{ route('filament.admin.resources.billings.index') }}";
                                },
                                onError: function(result) {
                                    alert('Payment failed. Please try again.');
                                },
                                onClose: function() {
                                    alert('You closed the payment window without completing the payment.');
                                }
                            });
                        };
                    </script>
                @else
                    <div class="mt-8 flex justify-center">
                        <div class="px-6 py-3 bg-green-100 text-green-800 font-medium rounded-lg">
                            Payment Completed
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-3xl font-bold text-gray-400">Billing Not Found</div>
            <p class="mt-4 text-gray-500">The requested billing information could not be found.</p>
            <a href="{{ route('filament.admin.resources.billings.index') }}"
                class="mt-6 inline-block px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition">
                Return to Billings
            </a>
        </div>
    @endif
</x-filament-panels::page>
