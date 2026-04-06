@extends('layouts.crm')

@section('title', 'Edit Lead')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.show', $lead) }}">{{ Str::limit($lead->title, 30) }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    @include('leads.create')
@endsection
