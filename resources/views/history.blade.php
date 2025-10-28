@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-warning mb-4">Kansas Weather History</h2>
    <div class="bg-dark text-white p-4 rounded">
        <p>{{ $todayHistory }}</p>
        <hr>
        <h4>More Historical Events</h4>
        <ul>
            @foreach($historicalEvents as $event)
            <li>{{ $event['date'] }} — {{ $event['summary'] }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection