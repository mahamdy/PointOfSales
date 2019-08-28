<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(){
        $categories_count=Category::count();
        $products_count=Product::count();
        $users_count=User::whereRoleIs('admin')->count();
        $clients_count=Client::count();

        $sales_data=Order::select(

            DB::raw('Year(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as sum')
        )->groupBy('month')->get();


    	return view('dashboard.welcome',compact('categories_count','products_count','users_count','clients_count','sales_data'));
    }//end of index
}//end of controller
