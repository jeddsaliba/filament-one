@extends('layouts.app')

@section('description', strip_tags($meta['description'] ?? $description ?? config('app.name')))
@section('keywords', strip_tags($meta['keywords'] ?? config('app.name')))
@section('author', strip_tags($meta['author'] ?? config('app.name')))
@section('title', $title ? config('app.name') . ' | ' . $title : config('app.name'))

@push('styles')
    <style>
        {!! $custom_css !!}
    </style>
@endpush
@section('content')
    {!! $content !!}
@endsection
@push('scripts')
    <script>
        {!! $custom_js !!}
    </script>
@endpush