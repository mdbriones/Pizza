<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    // foreach ($file = Storage::disk('public')->allFiles() as $file) {
        // if (pathinfo($file, PATHINFO_EXTENSION) == 'pml') {
            $fileContent = Storage::disk('public')->get('Order.pml');

            $file = \Illuminate\Support\Str::replace('{','<',$fileContent);
            $file = \Illuminate\Support\Str::replace('}','>',$file);
            $file = \Illuminate\Support\Str::replace('\\','/',$file);

            $xmlFile = simplexml_load_string($file);
            $json = json_encode($xmlFile);
            $orders = [
                "order" => json_decode($json,TRUE)
            ];

            array_walk($orders, function (&$itemOrder) {
                $itemOrder['attributes'] = $itemOrder['@attributes'];
                unset($itemOrder['@attributes']);

                array_walk($itemOrder['pizza'], function(&$itemPizza){
                    $itemPizza['attributes'] = $itemPizza['@attributes'];
                    unset($itemPizza['@attributes']);

                    if (isset($itemPizza['toppings'])) {
                        array_walk($itemPizza['toppings'], function(&$itemPizzaToppings){
                            $itemPizzaToppings['attributes'] = $itemPizzaToppings['@attributes'];
                            unset($itemPizzaToppings['@attributes']);
                            
                            switch ($itemPizzaToppings['attributes']['area']) {
                                case 0:
                                    $itemPizzaToppings['attributes']['area'] = "Whole";
                                    break;
                                case 1:
                                    $itemPizzaToppings['attributes']['area'] = "First-half";
                                    break;
                                case 2:
                                    $itemPizzaToppings['attributes']['area'] = "Second-half";
                                    break;
                            }
                        });
                    }
                });
             });

            //  dd($orders['order']['pizza'][0]['toppings'][0]['item']);
            
        // }
    // }


    return view('welcome', compact('orders'));
});
