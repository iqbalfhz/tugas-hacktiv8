<!-- filepath: resources/views/filament/pages/assessment-page.blade.php -->
<x-filament::page>
    @if ($classSession)
        <div class="mb-6 p-4 bg-white rounded-lg shadow">
            <h2 class="text-xl font-bold mb-2">Class Session Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Session ID:</p>
                    <p class="font-medium">{{ $classSession->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Course:</p>
                    <p class="font-medium">{{ $classSession->course->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date:</p>
                    <p class="font-medium">
                        {{ $classSession->session_date ? $classSession->session_date->format('d F Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-medium">Student Assessments</h3>
            <p class="text-sm text-gray-600">Enter grades and notes for each student below.</p>
        </div>

        {{ $this->table }}
    @else
        <div class="p-4 bg-yellow-100 text-yellow-700 rounded-lg">
            <p class="font-medium">No valid session ID provided. Please select a class session.</p>
        </div>
    @endif
</x-filament::page>
