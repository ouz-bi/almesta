@extends('layouts.admin')

@section('title', 'Modifier la catégorie')

@section('content')
    @livewire('admin.categories.category-form', ['category' => $category])
@endsection