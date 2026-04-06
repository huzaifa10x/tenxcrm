@extends('layouts.crm')

@section('title', 'Edit Activity')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.activities.index') }}">Activities</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    @include('activities._form', ['activity' => $activity, 'deals' => $deals, 'leads' => $leads])
@endsection
