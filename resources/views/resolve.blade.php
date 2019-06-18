@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session()->has('message'))
            <div class="alert alert-info">
                {{ session()->get('message') }}
            </div>
            @endif

            <div class="card">
                <div class="card-header">Confirm Supplier - {{ $supplier['name'] }}</div>
                <div class="card-body">
                    <h3>Confirm Supplier details</h3>
                    <p>Name: {{ $supplier['name'] }}</p>
                    <p>Description: {{ $supplier['description'] }}</p>
                    <br>
                    <h5>Resolved bank details - </h5>
                    <p>Account No: {{ $resolvedAccount['account_number'] }}</p>
                    <p>Account Name: {{ $resolvedAccount['account_name'] }}</p>
                    <br>
                    <div class="col-md">
                        <form action="/confirm" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="name" value="{{ $supplier['name'] }}">
                            <input type="hidden" name="description" value="{{ $supplier['description'] }}">
                            <input type="hidden" name="bank_code" value="{{ $supplier['bank_code'] }}">
                            <input type="hidden" name="account_number" value="{{ $supplier['account_number'] }}">

                            <input type="submit" name="submit" value="Confirm Supplier" class="btn btn-primary">
                        </form>
                        <a href="/"><button class="btn btn-danger">Cancel</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection