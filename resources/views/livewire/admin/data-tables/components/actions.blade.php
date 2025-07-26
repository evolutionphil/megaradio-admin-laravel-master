<div class="flex gap-4 w-auto">
    @php
        $buttonData = base64_encode(collect($row)->only('name', 'url', 'url_resolved')->toJson());
    @endphp

    <button class="cursor-pointer text-indigo-600 hover:text-indigo-900" @click="$store.radioPlayer.playRadio('{{ $buttonData }}')">Play</button>

    @if (empty($row->deleted_at))
        <a class="text-indigo-600 hover:text-indigo-900" href="{{ route('admin.radio-stations.similar-stations', $row->id) }}">Similar Stations</a>

        <a class="text-indigo-600 hover:text-indigo-900" href="{{ route('admin.radio-stations.show', $row->id) }}">View</a>
    @endif

    <a class="text-indigo-600 hover:text-indigo-900" href="{{ route('admin.radio-stations.edit', $row->id) }}">Edit</a>

    @if ($row->deleted_at != null)
        <a class="text-green-600 hover:text-indigo-900" href="{{ route('admin.radio-stations.restore', $row->id) }}">Restore</a>
    @endif
</div>
