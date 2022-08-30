<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Topping;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getAvailableOrders()
    {
        $orders = [];
        foreach(Storage::disk('public')->allFiles() as $file){
            if (pathinfo($file, PATHINFO_EXTENSION) == 'pml'){
                $orders[] = $file;
            }
        }

        return $orders;
    }

    public function index()
    {
        $orders = self::getAvailableOrders();
        return view('home', compact('orders'));
    }

    public function showFile()
    {
        $fileContent = Storage::disk('public')->get(request('filename'));
        return $fileContent;
    }

    public function convertToArray()
    {
        $orders = [];
        $file = \Illuminate\Support\Str::replace('{','<',request('pml'));
        $file = \Illuminate\Support\Str::replace('}','>',$file);
        $file = \Illuminate\Support\Str::replace('\\','/',$file);

        $xmlFile = simplexml_load_string($file);
        $json = json_encode($xmlFile);
        $orders = [
            "order" => json_decode($json,TRUE)
        ];

        return $orders;
    }

    public function convert()
    {
        $readableFile = self::convertToArray();
        $orders = self::formatArray($readableFile);
        $filename = request('filename');

        return view('order', compact('orders','filename'));
    }

    public function getTableColumns($table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    public function store()
    {
        $pml = $this->showFile();
        request()->request->add(['pml' => $pml]);
        $orders = self::formatArray(self::convertToArray());

        foreach($orders as $order){
            foreach($order['pizza'] as $pizza){
                Order::create([
                    'order_number' => $order['attributes']['number'],
                    'size' => $pizza['size'],
                    'crust' => $pizza['crust'],
                    'type' => $pizza['type'],
                    'toppings' => isset($pizza['toppings']) ? json_encode($pizza['toppings']) : null,
                ]);

                if(isset($pizza['toppings'])){
                    foreach($pizza['toppings'] as $toppings){
                        if(is_array($toppings['item'])){
                            $itemsArray = $toppings['item'];

                        } else {
                            $itemsArray = [$toppings['item']];
                        }
                        
                        foreach($itemsArray as $item){
                            $topping = new Topping();
                            $topping->order_number = $order['attributes']['number'];
                            $topping->toppings = $item;
                            $topping->count = 1;
                            $topping->save();
                        }
                    }
                }
            }
        }
        Storage::disk('public')->move(request('filename'), request('filename').'.bak');

        $orders = self::getAvailableOrders();
        return view('home', compact('orders'));
    }

    public function formatArray($readableFile)
    {
        array_walk($readableFile, function (&$itemOrder) {

            $itemOrder['attributes'] = $itemOrder['@attributes'];
            unset($itemOrder['@attributes']);

            if (!array_key_exists(0,$itemOrder['pizza'])){
                $temp = $itemOrder['pizza'];
                unset($itemOrder['pizza']);
                $itemOrder['pizza'][0] = $temp;
            }

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

        return $readableFile;
    }

    public function getPreviousOrder()
    {
        $orders = Order::all();

        foreach($orders as $order){
            $order->toppings = json_decode($order->toppings);
            if(isset($order->toppings)){
                foreach($order->toppings as $topping){
                    // dd($topping->item);
                    if(!is_array($topping->item)){
                        $topping->item = [$topping->item];
                    }
                }
            }
        }
        // dd($orders[0]->toppings[0]->item);
        return view('past-orders', compact('orders'));
    }

    public function toppingsUsed()
    {
        $toppingsUsed = Topping::groupBy('toppings')
                            ->selectRaw('toppings, sum(count) as sum')->get();


        return view('used-toppings', compact('toppingsUsed'));
    }
}
