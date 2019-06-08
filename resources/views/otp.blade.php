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
                <div class="card-header">Confirm Payment - {{ $supplier['name'] }}</div>
                <div class="card-body text-center">
                    @if(!$supplier)
                    <p>Supplier not found</p>
                    @else
                    <h3>An OTP has been sent to you for confirmation! </h3>
                    <br>
                    <div class="col-md-6 offset-3">
                        <form action="/{{$supplier['id']}}/pay/confirm" method="post">
                            {{ csrf_field() }}
                            <label for="amount">Enter OTP:</label>
                            <input type="text" name="otp" id="otp" class="form-control"><br>
                            <input type="hidden" value="{{ $transaction['transfer_code'] }}" name="transfer_code" />
                            <input type="submit" name="submit" value="Confirm" class="btn btn-primary">
                        </form>
                    </div>


                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection