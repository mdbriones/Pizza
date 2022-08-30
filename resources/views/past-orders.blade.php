@extends('layout.app')
@section('content')
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-4 sm:pt-0">

        <div class="container">
            <div class="row mt-5" style="display: flex; justify-content: center;">
                <label id="detail">ORDER DETAILS</label>
            </div>
            <div class="col-sm">
                <div class="row mb-3">
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8" style="width: 100%;">
                        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                            <table class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Crust</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Toppings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $order->order_number }}</th>
                                        <td>{{ $order->size }}</td>
                                        <td>{{ $order->crust }}</td>
                                        <td>{{ $order->type }}</td>
                                        @if(isset($order->toppings))
                                            @foreach($order->toppings as $topping)
                                                @foreach($topping->item as $item)
                                                <td>{{ $item }}</td>
                                                @endforeach
                                            @endforeach
                                        @endif
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