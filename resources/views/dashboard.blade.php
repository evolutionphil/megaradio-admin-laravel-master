<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        <div>
            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Visits</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($total_visitors) }}</dd>
                </div>

                <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Social Shares</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($total_shares) }}</dd>
                </div>

                <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Stations</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($total_stations)}}</dd>
                </div>
            </dl>
        </div>

        @if ($daily_visitors_chart)
            <div class="h-64 mt-5 px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                {!! $daily_visitors_chart->container() !!}
            </div>
        @endif

        <div class="mt-5 flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th colspan="2" scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Most visited stations</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($top_visited_stations as $station)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-indigo-600 sm:pl-6">
                                        <a href="{{ route('admin.radio-stations.edit', $station->id) }}">/radio-stations/{{ $station->id }}</a>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $station->total_visits }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.umd.js" integrity="sha512-gQhCDsnnnUfaRzD8k1L5llCCV6O9HN09zClIzzeJ8OJ9MpGmIlCxm+pdCkqTwqJ4JcjbojFr79rl2F1mzcoLMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        @if ($daily_visitors_chart)
            {!! $daily_visitors_chart->script() !!}
        @endif
    @endpush
</x-app-layout>
