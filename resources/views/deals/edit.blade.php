@extends('layouts.crm')

@section('title', 'Edit Deal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.deals.index') }}">Deals</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.deals.show', $deal) }}">{{ Str::limit($deal->title, 30) }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    @include('deals.create')
@endsection
