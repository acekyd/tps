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
            @if (1)
            <div class="alert alert-warning" role="alert">
                You have not added any suppliers yet
            </div>
            @endif
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
                                <th scope="col">#</th>
                                <th scope="col">Supplier name</th>
                                <th scope="col">Description</th>
                                <!-- <th scope="col">Last payment</th> -->
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                                <td>
                                    <button class="btn btn-primary">Pay</button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <!-- <td>@mdo</td> -->
                                <td>
                                    <button class="btn btn-primary">Pay</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection