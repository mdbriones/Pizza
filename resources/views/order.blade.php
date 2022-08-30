@extends('layout.app')
@section('content')
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-4 sm:pt-0">

        <div class="container">
            <div class="row mt-5" style="display: flex; justify-content: center;">
                <label id="detail">ORDER DETAILS</label>
            </div>
            <div class="col-sm">
                <div class="row mb-3">
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8" style="width: 50%;">
                        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                            @if(isset($orders))
                                @foreach ($orders as $order)
                                    <div style="text-indent: 10%;">Order {{ $order['attributes']['number'] }}:</div>
                                    
                                    @foreach ($order['pizza'] as $pizza)
                                        <div class="{{ $loop->first ? 'mt-0' : 'mt-8' }}">
                                            <div style="text-indent: 15%;">
                                                Pizza {{ $pizza['attributes']['number'] }} - {{ $pizza['size'] }}, {{ $pizza['crust'] }}, {{ $pizza['type'] }}
                                            </div>
                                            @if($pizza['type'] == 'custom')
                                            <div style="text-indent: 20%;">
                                                @foreach ($pizza['toppings'] as $topping)
                                                    <div>
                                                        Toppings {{ $topping['attributes']['area'] }}

                                                        @if(gettype($topping['item']) == 'array')
                                                            @foreach($topping['item'] as $toppingItem)
                                                            <div style="text-indent: 30%;">{{ $toppingItem }}</div>
                                                            @endforeach
                                                        @else
                                                            <div style="text-indent: 30%;">{{ $topping['item'] }}</div>
                                                        @endif
                                                        
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach 
                                    
                                @endforeach 
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-sm">
            <div class="row mt-5" style="display: flex; justify-content: center;">
                <div style="display: flex;">
                    <a href="/" type="button" class="btn btn-sm btn-default">Back</a>
                    <form method="POST" action="{{ route('order.store', $filename) }}">
                        <input type="hidden" name="filename" value="{{ $filename }}">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-sm btn-warning">
                            <span class="fa-solid fa-floppy-disk"></span>
                            Save and exit
                        </button>
                    </form>
                </div>
            </div>
            </div>
        </div>
        
    </div>
@endsection