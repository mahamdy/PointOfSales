<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Client $client)
    {
        $categories=Category::with('products')->get();
        $orders=$client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create',compact(['categories','client','orders']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Client $client)
    {
        $request->validate([
            'products'=>'required|array',
        ]);

        $this->attach_order($request, $client );

        session()->flash('success',__('site.added_successfully'));

        return redirect()->route('dashboard.orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client , Order $order)
    {
        $categories=Category::with('products')->get();
        $orders=$client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit',compact('client','order','categories','orders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Client $client, Order $order)
    {
        $request->validate([
            'products'=>'required|array',
        ]);

        $this->detach_order($order);

        $this->attach_order($request, $client );

        session()->flash('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.orders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client,Order $order)
    {
        //
    }

    private function attach_order($request,$client){

        $order=$client->orders()->create([]);


        $order->products()->attach($request->products);


        $total_price=0;
        foreach ($request->products as $id=>$quantity){

            $product=Product::FindOrFail($id);
            $total_price +=$product->sale_price * $quantity['quantity'];
            $product->update([
                'stock'=>$product->stock - $quantity['quantity']
            ]);

        }

        $order->update([
            'total_price'=>$total_price
        ]);

    }//end of attach function

    private function detach_order($order){
        foreach ($order->products as $product) {

            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);

        }//end of for each

        $order->delete();
    }
}
