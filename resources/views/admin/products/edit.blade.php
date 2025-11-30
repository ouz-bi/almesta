@extends('layouts.admin')

@section('title', 'Modifier le produit')

@section('content')
    @livewire('admin.products.product-form', ['product' => $product])
@endsection