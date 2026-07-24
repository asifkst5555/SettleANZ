@props(['headers' => []])

<div class="sz-table-wrap">
    <table class="sz-table" {{ $attributes }}>
        @if (count($headers) > 0)
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
