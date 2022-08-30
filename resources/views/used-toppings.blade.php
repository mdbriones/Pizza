@extends('layout.app')
@section('content')
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-4 sm:pt-0">

        <div class="container">
            <div class="row mt-5" style="display: flex; justify-content: center;">
                <label id="detail">Toppings Used</label>
            </div>
            <div class="col-sm">
                <div class="row mb-3">
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8" style="width: 50%;">
                        <div class="mt-8 bg-white d-flex overflow-hidden shadow sm:rounded-lg">
                            <table class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col">Toppings</th>
                                        <th scope="col">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($toppingsUsed as $topping)
                                    <tr>
                                        <td>{{ $topping->toppings }}</td>
                                        <td>{{ $topping->sum }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
            <div class="row mt-5" style="display: flex; justify-content: center;">
                <div style="display: flex;">
                    <a href="/" type="button" class="btn btn-sm btn-default">Back</a>
                </div>
            </div>
            </div>
        </div>
        
    </div>
@endsection