@extends('layout.app')
@section('content')
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 py-4 sm:pt-0">
    <div class="container">
        <div class="row">
            
            <div class="col-sm">
                <div class="row mb-3">
                    <div class="px-2" style="width: 120px;">
                        <a href="{{ route('file.index') }}" class="btn btn-success btn-sm">Refresh orders</a>
                    </div>
                    <div class="px-2" style="width: 120px;">
                        <a href="{{ route('used.toppings') }}" class="btn btn-info btn-sm">Used Toppings</a>
                    </div>
                    <div class="px-2" style="width: 120px;">
                        <a href="{{ route('order.previous') }}" class="btn btn-info btn-sm">Previous Orders</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div style="width: 80%; ">
                        <div class="list-group">
                            @foreach($orders as $order)
                                <a href="#" 
                                    class="files list-group-item list-group-item-action">{{$order}}</a>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-sm">
                <div class="row d-flex mb-3">
                    <div class="mr-auto">
                        <label id="filename">&nbsp;</label>
                    </div>
                    <div class="">
                        <a style="display: none;" href="#" id="convert"
                                type="button"
                                class="btn btn-primary btn-sm">Process</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div>
                        <textarea class="form-control" style="font-size: 10px; background: white; outline: none; width: 100%"
                            name="pmlFile" 
                            id="pmlFile" 
                            cols="70" 
                            rows="25"></textarea>
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(".files").click(function(){
            $.get("/file-show/", {filename:this.text}, function(data){
                $('#pmlFile').text(data);
                $('#convert').show();
            });
            $('#filename').text(this.text);
        });

        $("#convert").click(function(){
            let pml = $('#pmlFile').text();
            let filename = $('#filename').text();
            window.location.href = "/file-convert?pml="+pml+"&filename="+filename;
        });
    });
</script>