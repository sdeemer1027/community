<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Find Members
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Find Neighbors, Friends and Family
                </h3>

                <p class="text-gray-600 mb-6">
                    Search for registered members by name or ZIP code.
                    You choose whether to send a connection request.
                </p>

                <form method="GET" action="{{ route('members.index') }}" class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Enter a name or ZIP code"
                            class="border-gray-300 rounded-md shadow-sm w-full"
                        >

                        <button
                            type="submit"
                            class="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('members.index') }}"
                            class="border border-gray-300 px-5 py-2 rounded-md text-center"
                        >
                            Show All
                        </a>
                    </div>
                </form>

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

                @if ($members->isEmpty())
                    <p class="text-gray-600">
                        No members found. Try another name or ZIP code.
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($members as $member)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        {{ $member->name }}
                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        ZIP code:
                                        {{ $member->zip_code ?: 'Not provided' }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('connections.store') }}">
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="recipient_id"
                                        value="{{ $member->id }}"
                                    >

                                    <button
                                        type="submit"
                                        class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                                    >
                                        Request Connection
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $members->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>