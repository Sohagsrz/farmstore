<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


  
use App\Models\Category_Relation;
use App\Models\Srz_Cpt; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Category;
use App\Models\User;

class ProductsController extends Controller
{
    
    public $type = 'products';
    //farmer single view
    public function farmerSingle($farmer){
        $farmer = User::find($farmer);
        if(!$farmer){
            redirect("/");
        }
        $type = 'products';
        $products = Srz_Cpt::where([
            'post_type' => $type,
            'post_author' => $farmer->id
        ])->orderBy('id','desc')->get();
        return view('pages.farmer', compact('farmer', 'products'));

    }
    // order view
    public function order( $order)
    {
        $order = Srz_Cpt::find($order);
        $order->items = json_decode(get_field('cart', 'orders', $order->id));
        
        return view('pages.orderView', compact('order'));
    }

    //orders
    public function orders()
    {
        $type= "orders";
        $name= 'Orders';
        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_orders= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            ])->count();
        $total_pages= ceil($total_orders/$limit);
        $orders= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        return view('pages.orders', compact('orders','type','name','total_orders','total_pages'));
    }


    //checkout
    public function checkout(Request $request)
    {
        $cart = \Cart::session(Auth::id())->getContent();
        $total = \Cart::session(Auth::id())->getTotal();
        $user = Auth::user();
        return view('pages.checkout', compact('cart', 'total', 'user'));
    }
    //checkoutProcess
    public function checkoutProcess(Request $request)
    {
        $cart = \Cart::session(Auth::id())->getContent();
        $total = \Cart::session(Auth::id())->getTotal();
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'paymentMethod' => 'required'
        ]);
        // create order
        $order = new Srz_Cpt();
        $order->post_title = 'Order #'.time();
        $order->post_content = json_encode($cart);
        $order->post_type = 'orders';
        $order->post_author = Auth::id();
        $order->post_status = 'pending';
        $order->save();
        // add meta
        update_field('name', $request->input('name'), 'orders', $order->id);
        update_field('email', $request->input('email'), 'orders', $order->id);
        update_field('phone', $request->input('phone'), 'orders', $order->id);
        update_field('address', $request->input('address'), 'orders', $order->id);
        update_field('city', $request->input('city'), 'orders', $order->id);  
        update_field('paymentMethod', $request->input('paymentMethod'), 'orders', $order->id);

        //cart data 
        update_field('cart', json_encode($cart), 'orders', $order->id);
        // total
        update_field('total', $total, 'orders', $order->id);

        // clear cart
        \Cart::session(Auth::id())->clear();
        // redirect with success message
        return redirect()->route('orders')->with('success', 'Order placed successfully');
    }
    // dashboard  farmer

    public function dashboard (){
        
        $type= "products";
        $name= 'Products';
        $total_products= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->count();
        $total_orders = Srz_Cpt::where([
            'post_type' =>'orders',
            'post_author' => Auth::id()
            // 'status' => 1
            ])->count();
        $products = Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->orderBy('id','desc')
            ->limit(10)
            ->get();
        return view('pages.farmer.dashboard', compact('products','type','name','total_products','total_orders'));
    }
    // add products
    public function addfromdashboard()
    {
        $type= "products";
        $name= 'Products';
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
        
        return view('pages.farmer.add-product', compact('categories','type','name'));
    }
    //storefromdashboard // save farmer product data
    public function storefromdashboard(Request $request){
        $type = 'products';
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required', 
            'uploadImg' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $product = new Srz_Cpt();
        $product->post_title = $request->input('post_title');
        $product->post_content = $request->input('post_content');
        $product->post_type = 'products';
        $product->post_author = Auth::id();
        $product->post_status = 'publish';
        $product->save();
        //add categories
        $category= $request->input('categories');
        if($category){
             
                 $product->categories()->attach($category);
            
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $product->save();
        // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $product->id);
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), $type, $product->id);
            }
        }

        // price 
        $price = $request->input('price');
        if($price){
            update_field('price', $price, $type, $product->id);
        }
        //quantity
        $quantity = $request->input('quantity');
        if($quantity){
            update_field('quantity', $quantity, $type, $product->id);
        }

        // meta array 
        $meta = $request->input('meta' , []);
        foreach($meta as $key => $value){
            update_field($key, $value, $type, $product->id);
        }

        
     
        
        //redirect with success message
        return redirect()->route('farmer.editfromdashboard', [
        $product
        ])->with('success', 'Product added successfully');
    }
    //addToCart
    public function addToCart(Request $request,Srz_Cpt $product){ 
        // validate
        $request->validate([
            'quantity' => 'required|numeric|min:'. get_field('min_order','products', $product->id, 1)
        ]);

        // if cart is empty then this the first product
        if(\Cart::isEmpty()) {
            \Cart::session(Auth::id())->add(array(
                'id' => $product->id,
                'name' => $product->post_title,
                'price' => get_field('price', 'products', $product->id,0),
                'quantity' => $request->get('quantity', 1),
                'attributes' => array(),
                'associatedModel' => $product
            ));

            return redirect()->route('cart')->with('success', 'Product added to cart successfully!'); 
        }
        // if cart not empty then check if this product exist then increment quantity
        if(\Cart::get($product->id) != null) { 
            \Cart::session(Auth::id())->update($product->id, array(
                'quantity' => $request->get('quantity', 1)
            ));
            
        // redirect with success message IN CART PAGE
        return redirect()->route('cart')->with('success', 'Product added to cart successfully!'); 
                }
                
        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');  
    }
    //removeFromCart
    public function removeFromCart(Srz_Cpt $product){
        \Cart::session(Auth::id())->remove($product->id);
        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }
    //updateCart
    public function updateCart(Request $request, Srz_Cpt $product){
        // validate
        $request->validate([
            'quantity' => 'required|numeric|min:'. get_field('min_order','products', $product->id, 1)
        ]);
        
     


         
            \Cart::session(Auth::id())->update($product->id, array(
                'quantity' => array(
                    'relative' => false,
                    'value' =>  $request->get('quantity', 1)
                )
            ));
            
        return redirect()->back()->with('success', 'Cart updated successfully!');  
    }


    //cart
    public function cart(){
        $cart = \Cart::session(Auth::id())->getContent();
        return view('pages.cart', compact('cart'));
    }
    // emptyCart
    public function emptyCart(){
        \Cart::session(Auth::id())->clear();
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    //editfromdashboard
    public function editfromdashboard(Request $request,Srz_Cpt $product)
    {
        $name = 'Product';
        $type = 'products';
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get(); 
        $selected_categories = Category_Relation::where('post_id', $product->id)->pluck('category_id')->toArray();
        return view('pages.farmer.edit-product', compact('product', 'categories', 'selected_categories','type','name'));
    }
    // updatefromdashboard
    public function updatefromdashboard(Request $request, Srz_Cpt $product)
    {
        $name = 'Product';
        $type = 'products';
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
            'uploadImg' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $product->post_title = $request->input('post_title');
        $product->post_content = $request->input('post_content');
        $product->post_author = Auth::id();
        $product->post_status = $request->input('post_status', 'publish');
        $product->save();
        //update categories
        $category = $request->input('category');
        if($category){  
                 $product->categories()->attach($category);
            
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $product->save();
            // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $product->id);
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), 'products', $product->id);
            }
        }
        // price 
        $price = $request->input('price');
        if($price){
            update_field('price', $price, $type, $product->id);
        }
        //quantity
        $quantity = $request->input('quantity');
        if($quantity){
            update_field('quantity', $quantity, $type, $product->id);
        }

        // meta array 
        $meta = $request->input('meta' , []);
        foreach($meta as $key => $value){
            if(
                empty($value)
            ){
                continue;
            }
            update_field($key, $value, $type, $product->id);
        }
        //redirect with success message
        return redirect()->
        route('farmer.editfromdashboard', [
            $product
        ])->with('success', 'Product updated successfully');
    }
    // deletefromdashboard
    public function deletefromdashboard(Srz_Cpt $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
    //productslist 
    public function productslist()
    {
        $type= "products";
        $name= 'Products';
        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_products= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->count();
        $total_pages= ceil($total_products/$limit);
        $products= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        return view('pages.farmer.products-list', compact('products','type','name','total_products','total_pages'));
    }
    //orderslist
    public function orderslist()
    {
        $type= "orders";
        $name= 'Orders';
        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_orders= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->count();
        $total_pages= ceil($total_orders/$limit);
        $orders= Srz_Cpt::where([
            'post_type' =>$type,
            'post_author' => Auth::id()
            // 'status' => 1
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        return view('pages.farmer.orders-list', compact('orders','type','name','total_orders','total_pages'));
    }
    // settings
    public function settings()
    {
        $type= "products";
        $name= 'Products';
        $user = Auth::user();
        return view('pages.farmer.settings', compact('type','name','user'));
    }
    // settingsProcess
    public function settingsProcess(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'uploadImg' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $user = Auth::user();
        $user->name = $request->input('name'); 
        if($request->input('password')){
            // confimrm pass check
            $request->validate([
                'password' => 'required|confirmed'
            ]); 
            $user->password = bcrypt($request->input('password'));
        }


        $user->save();
        // field meta datas
        $meta = $request->input('meta' , []);
        foreach($meta as $key => $value){
            if(empty($value)) continue;

            update_field($key, $value, 'user',  Auth::id());
        }
        // image
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            
            // set thumbnail url to custom field
            update_field('profile_pic', $public_img_url, 'user', Auth::id());
        }


        

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function index()
    { 
        $type= "products";
        $name= 'Products';


        $page =  request()->get('page',1);
        $page= max(1,$page);
        $limit= env('PAGINATION_LIMIT',10);
        $offset= ($page-1)*$limit;
        $total_products= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->count();
        $total_pages= ceil($total_products/$limit);
        $products= Srz_Cpt::where([
            'post_type' =>$type,
            // 'status' => 1
            ])->orderBy('id','desc')->paginate(
                $limit,
                ['*'],
                'page',
                $page
            );
        
        return view('admin.pages.products.index', compact('products','type','name','total_products','total_pages'));
   
  

    }
    // add 
    public function add()
    {
        $type= "products";
        $name= 'Movie';
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
        return view('admin.pages.products.add-movie', compact('categories','type','name'));
    
    }

    //store 
    public function store(Request $request)
    {  
        
        $type = 'products';

        //validate request
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
            'categories' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        //add movie
        $movie = new Srz_Cpt();
        $movie->post_title = $request->input('post_title');
        $movie->post_content = $request->input('post_content');
        $movie->post_type = 'products';
        $movie->post_author = Auth::id();
        $movie->post_status = 'publish';
        $movie->save();
        //add categories
        $categories = $request->input('categories');
        
        
        if($categories){ 
            foreach($categories as $category){
                 $movie->categories()->attach($category);
            }
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
         
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $movie->save();
        // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $movie->id);


            //set thumbNail

            // $movie->thumbnail = $name;
            // $movie->save();
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), $type, $movie->id);
            }
             
        }
        //watch urls
        $watch_urls = $request->input('watch_urls');
        if($watch_urls){
            //remove empty values
            $watch_urls = array_filter($watch_urls);

             update_field('watch_urls',  ($watch_urls), $type, $movie->id);
        }
        //download urls
        $download_urls = $request->input('download_urls');
        if($download_urls){
            //remove empty values
            $download_urls = array_filter($download_urls);

            update_field('download_urls',  ($download_urls), $type, $movie->id);
        }


        //redirect with success message
        return redirect()->route('admin.products.edit', [
            'id' => $movie->id
        
        ])->with('success', 'Movie added successfully');

    }
    // edit 
    public function edit(Request $request, $id)
    {

        $name = 'Movie';
        $type = 'products';

        
        $categories = Category::where('type', $type)
        ->orderBy('name', 'asc')
        ->get();
 
        $movie = Srz_Cpt::find($id);
        $selected_categories = Category_Relation::where('post_id', $id)->pluck('category_id')->toArray();
        
        return view('admin.pages.products.edit-movie', compact('movie', 'categories', 'selected_categories','type','name'));

    }
    // update
    public function update(Request $request, $id)
    {
        $name = 'Movie';
        $type = 'products';
        $request->validate([
            'post_title' => 'required',
            'post_content' => 'required',
            'categories' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $movie = Srz_Cpt::find($id);
        $movie->post_title = $request->input('post_title');
        $movie->post_content = $request->input('post_content');
        $movie->post_author = Auth::id();
        $movie->post_status = $request->input('post_status', 'publish');

        $movie->save();
        //update categories
        $categories = $request->input('categories');
 
        if($categories){
            $movie->categories()->detach();
            foreach($categories as $category){
                 $movie->categories()->attach($category);
            }
        }
        // image upload
        if($request->hasFile('uploadImg')){
            $image = $request->file('uploadImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $public_img_url = asset('uploads/'.$name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name); 
            $movie->save();
            // set thumbnail url to custom field
        update_field('thumbnail', $public_img_url, $type, $movie->id);
        }else{
            // set thumbnail url to custom field
            if($request->get('img', '')){
                update_field('thumbnail', $request->get('img', ''), 'products', $movie->id);
            }
        }
        //watch urls
        $watch_urls = $request->input('watch_urls', []);
        if($watch_urls){
            $watch_urls = array_filter($watch_urls);
            update_field('watch_urls',  ($watch_urls), 'products', $movie->id);
        }
        //download urls
        $download_urls = $request->input('download_urls', []);
        if($download_urls){
            
            $download_urls = array_filter($download_urls);
            update_field('download_urls',  ($download_urls), 'products', $movie->id);
        }
        //redirect with success message

        return redirect()->back()->with('success', 'Movie updated successfully');
    }
    // delete
    public function delete($id)
    {
        $movie = Srz_Cpt::find($id);
        $movie->delete();
        return redirect()->back()->with('success', 'Movie deleted successfully');
    }
    // search
    public function search(Request $request)
    {
        $search = $request->input('q');
        $products = Srz_Cpt::where('post_title', 'like', '%'.$search.'%')
        ->where(
            'post_type', 'products'	
        )
        ->get();
        return view('search', compact('products', 'request'));
    }

    // single view
    public function single(Srz_Cpt $product)
    { 

        // views  field
        $views = get_field('views','products', $product->id);
        if($views){
            update_field('views', intval($views)+1, 'products', $product->id);
        }else{
            update_field('views', 1, 'products', $product->id);
        }
        $type = $product->post_type; 
        //REALTED PRODUCTS based on category
        $related = Srz_Cpt::whereHas('categories', function($q) use($product){
            $q->whereIn('category_id', $product->categories->pluck('id')->toArray());
        })->where('id', '!=', $product->id)->limit(5)->get();
        
        return view('pages.single-product', compact('product' , 'related'));
    }
   


}
