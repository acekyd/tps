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
                <div class="card-header">Pay Supplier - {{ $supplier['name'] }}</div>
                <div class="card-body text-center">
                    @if(!$supplier)
                    <p>Supplier not found</p>
                    @else
                    <h3>Are you sure you want to pay {{ $supplier['name'] }}? </h3>
                    <p>{{ $supplier['description'] }}</p>
                    <br>
                    <div class="col-md-6 offset-3">
                        <form action="/{{$supplier['id']}}/pay" method="post">
                            {{ csrf_field() }}
                            <label for="amount">Enter amount:</label>
                            <input type="number" name="amount" id="amount" min="100" step="100" class="form-control"><br>
                            <input type="submit" name="submit" value="Make Payment" class="btn btn-primary">
                        </form>
                    </div>


                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Account Balance</div>
                <div class="card-body text-center">
                    Your account balance is <br>
                    <h3>{{ $balance['currency'] }}<br> {{number_format($balance['balance']) }}</h3>
                </div>
            </div>
        </div>
    </div>
        @endsection