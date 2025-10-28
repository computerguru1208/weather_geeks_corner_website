@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        @if($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}">
        @endif
        <div class="card-body">
            <h2 class="card-title">{{ $post->title }}</h2>
            <p class="text-muted">Published {{ $post->created_at->diffForHumans() }}</p>
            <p class="card-text">{!! nl2br(e($post->content)) !!}</p>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary mt-3">← Back to Blog</a>
        </div>
    </div>
</div>
@endsection