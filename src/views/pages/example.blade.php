@extends('banners::components.layout')
@section('banner_page')
    @banner('data.title', 'Title')
    @banner('data.multilang', 'Multilang')
    @banner('data.image', 'Image')
    @cards(['banners'=>'card', 'title'=>'Multiple'])
        @banner('title', 'Title')
    @endcards
@endsection
