@props(['station'])

@php
    $buttonData = base64_encode(collect($station)->only('name', 'url', 'url_resolved')->toJson());
@endphp

<button x-data type="button" class="cursor-pointer text-indigo-600 hover:text-indigo-900" @click="$store.radioPlayer.playRadio('{{ $buttonData }}')">Play</button>
