<x-filament::page>
    <div class="bg-white rounded-xl shadow mb-4 p-4">
        <h2 class="text-lg font-bold mb-2">Class Enrollment Management</h2>

        <div>
            {{ $this->form }}
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow">
            <div class="p-4">
                <h2 class="text-lg font-bold mb-2">Available Student</h2>
                <livewire:available-student />
            </div>
        </div>

        <div class="bg-white rounded-xl shadow">
            <div class="p-4">
                <h2 class="text-lg font-bold mb-2">Enrolled Student</h2>
                <livewire:enrolled-student />
            </div>
        </div>
    </div>
</x-filament::page>
