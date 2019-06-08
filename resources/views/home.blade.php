@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Add Supplier</div>

                <div class="card-body">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <label for="name">
                            Name: <br>
                            <input type="text" name="name" />
                        </label>
                        <label for="description">
                            Description:<br>
                            <textarea name="description" id="description" cols="25" rows="4"></textarea>
                        </label>
                        <label for="bank">
                            Bank: <br>
                            <select name="bank_code" id="bank_code">
                                <option value="">-- Select bank --</option>
                                @foreach ($banks as $bank)
                                <option value="{{ $bank['code'] }}">{{ $bank['name']}}</option>
                                @endforeach

                            </select>
                        </label>
                        <label for="acc_no">
                            Account no: <br>
                            <input type="text" name="account_number" />
                        </label>
                        <input type="submit" value="submit">
                    </form>
                </div>
            </div>
        </div>
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
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Supplier name</th>
                                <th scope="col">Description</th>
                                <!-- <th scope="col">Last payment</th> -->
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier['name']}}</td>
                                <td>{{ $supplier['description']}}</td>
                                <td>
                                    <a href="/{{$supplier['id']/pay">
                                        <button class="btn btn-primary">Pay</button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection