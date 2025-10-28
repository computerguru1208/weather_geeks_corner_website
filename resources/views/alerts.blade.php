@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-white mb-4">Active Weather Alerts</h2>
    @foreach($groupedAlerts as $event => $items)
    <div class="card bg-dark text-white mb-3">
        <div class="card-header text-warning">{{ $event }}</div>
        <ul class="list-group list-group-flush">
            @foreach($items as $alert)
            <li class="list-group-item bg-dark text-white">
                <strong>{{ $alert['properties']['headline'] }}</strong><br>
                {{ $alert['properties']['description'] }}<br>
                <em>Expires: {{ $alert['properties']['expires'] }}</em>
            </li>
            @endforeach
        </ul>
    </div>
    @endforeach
</div>
@endsection