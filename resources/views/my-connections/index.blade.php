
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Connections
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    People You Are Connected With
                </h3>

                <p class="text-gray-600 mb-6">
                    These members have accepted your connection request,
                    or you accepted theirs.
                </p>

                @if ($connections->isEmpty())
                    <p class="text-gray-600">
                        You have no accepted connections yet.
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($connections as $connection)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900">
                                    @if ($connection->requester_id === $userId)
                                        {{ $connection->recipient->name }}
                                    @else
                                        {{ $connection->requester->name }}
                                    @endif
                                </h4>

                                <p class="text-sm text-green-700 mt-1">
                                    Connected
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
