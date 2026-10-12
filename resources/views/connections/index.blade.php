<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Connection Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Incoming Requests
                </h3>

                <p class="text-gray-600 mb-6">
                    People who want to connect with you appear here.
                    You decide whether to accept their request.
                </p>

                @if (session('success'))
                    <div class="mb-4 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has('connection'))
                    <div class="mb-4 text-red-700">
                        {{ $errors->first('connection') }}
                    </div>
                @endif

                @if ($requests->isEmpty())
                    <p class="text-gray-600">
                        You have no pending connection requests.
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($requests as $connection)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900">
                                    {{ $connection->requester->name }}
                                </h4>

                                <p class="text-sm text-gray-600 mt-1">
                                    Sent you a connection request.
                                </p>

                                <div class="flex flex-wrap gap-3 mt-4">
                                    <form method="POST" action="{{ route('connections.accept', $connection) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" style="background-color: #15803d; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold;">
                                            Accept
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('connections.decline', $connection) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" style="background-color: #b91c1c; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold;">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
