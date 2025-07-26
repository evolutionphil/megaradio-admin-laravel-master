<form action="{{ $attributes->get('route') }}" method="POST" class="inline">
    @method('DELETE')
    @csrf
    <x-button onclick="confirm('Are you sure?')">Delete</x-button>
</form>
