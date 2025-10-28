@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-white mb-4">Weather Blog</h2>
    @foreach($posts as $post)
    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $post['title'] }}</h4>
            <p>{{ $post['excerpt'] }}</p>
            <a href="{{ route('blog.show', $post['slug']) }}" class="btn btn-warning">Read More</a>
        </div>
    </div>
    @endforeach
</div>
@endsection