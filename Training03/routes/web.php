<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Jobs\ProccessTest;
use App\Jobs\ProccessTest2;
use App\Exports\ProductPExport;
use Illuminate\Support\Facades\Bus;
use App\Events\TestEvent;
use App\Events\MyEvent;
use App\Events\MyEvent2;
use App\Events\NewEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestNotification;
use App\Models\User;
use App\Events\RegisterSuccess;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UsersController;
use App\Models\Message;
use App\Scraper\GOPO;
use App\Http\Controllers\ProductController;
use App\Models\WooProduct;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
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
//
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function() {
    //test queue
    Route::get('test-queue', function(Request $request) {
        $user = $request->user();
        // $a  = false;
        // ProccessTest::dispatchUnless($a == false, $user);
        // ProccessTest::dispatchIf($a == true, $user);
        ProccessTest::dispatch($user);
        // ProccessTest::dispatch($user)->delay(now()->addMinutes(2));

        //dispatch after response
        // ProccessTest::dispatchAfterResponse($user);
        // echo 'done';

        // Bus::chain([
        //     new ProccessTest($user),
        //     new ProccessTest2($user),
           
        // ])->dispatch();

        ProccessTest::dispatch($user)->onQueue('new');
    });

    Route::get('test-event', function(Request $request){
        $user = $request->user();
        TestEvent::dispatch($user);
    });

    Route::get('test-notification', function(Request $request){
        $user = $request->user();

        //gui notification
        // Notification::send($user, new TestNotification());
        // $delay = now()->addMinutes(2);

        $user->notify(new TestNotification());
        // $user->notify((new TestNotification())->delay($delay));
        echo 'done';
    });


    Route::get('register-success', function(Request $request) {
        $user = $request->user();

        RegisterSuccess::dispatch($user);
    });
});


Route::get('test', function () {
    //  event(new App\Events\StatusLiked("WORF"));
    event(new MyEvent('hello world'));
    // return "Event has been sent!";
});
Route::get('counter', function(){
    return view('broadcast.counter');
});

Route::get('sender', function(){
    return view('broadcast.sender');
});

Route::post('sender', function(Request $request){
    $text = $request->text;
    // MyEvent::dispatch($text);
    event(new MyEvent($text));
    return view('broadcast.success')->with(['data' => $text]);
});

Route::get('eloquent', function(Request $request){
    $user = User::findOrFail(1);
    echo '<pre>';
    print_r($user);
    echo '</pre>';
    dd($user->first_name);
    dd($user->name_upper_case);
});

Route::get('hash', function(){
    dd(Hash::make('123456'));
});

Route::get('users/export', [UsersController::class, 'export']);

Route::get('test-ev', function() {
    event(new MyEvent2('hello world'));
    echo 'adsfj';
});
Route::middleware(['auth'])->group(function () {
    Route::get('test-pusher', function() {
        return view('broadcast2.index');
    });
    

    Route::get('test-pc', function() {
        event(new NewEvent(Message::findOrFail(1)));
    });
});

Route::get('/tk', function(){
    $user = User::find(1);
    $token = $user->createToken('Token Name')->accessToken;
    dd($token);
});
Route::get('/crawl', function() {
    $tg = new GOPO;
    $tg->scrape3D();
});

Route::get('/test-w', function() { 

    // for($i = 1; $i <=12; $i++) {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, 'https://www.teeqtveteran.com/api/catalog/products_v2.json?sort_field=name&sort_direction=asc&collection_ids=86734960891');
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $jsonData = json_decode(curl_exec($curlSession));
        // echo count($jsonData);
        echo '<pre>';
        print_r($jsonData->products[0]);
        echo '</pre>';die;
        foreach($jsonData->products as $product) {
            $name = $product->title;
            $description = $product->body_html;

            // $tags = "";
            // foreach($product->tags as $tag)
            //     $tags .= $tag .',';
            // $tags = substr($tags, 0, -1);
            // echo $i . ' ' . $tags;
            // echo '<br>';
            // echo $i .'<br>';
            $images  = "";
            foreach ($product->images as $image)
                $images .= $image->src. ',';
            $images = substr($images, 0, -1);

            $price = $product->price;
            
            $p = new App\Models\ProductW;
            $p->name = $name;
            $p->image = $images;
            $p->short_description = $description;
            $p->regular_price = $price;

            $p->save();
            // die;
        }

        curl_close($curlSession);
    // }
     
    dd('success');
});

Route::get('products/export', [ProductController::class, 'export']);
Route::get('products/export-from-array', [ProductController::class, 'exportP']);


Route::get('/fashion-shop', function() { 

    // for($i = 1; $i <=12; $i++) {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, 'https://www.teeqtveteran.com/api/catalog/products_v2.json?ids=1000000152415241,1000000149532509,1000000150184164,1000000155197833,1000000179158258,1000000179161568,1000000179161264,1000000154749262,1000000180516652,1000000148138814,1000000148375264,1000000149941765');
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

        $jsonData = json_decode(curl_exec($curlSession));
        echo '<pre>';
        print_r($jsonData);
        echo '</pre>';die;
        foreach($jsonData->products as $product) {
            $product_name = $product->title;
            $product_des = $product->body_html;

            //tao 1 dong gom ten sp va mo ta truoc
            $option_sets = [] ;
            // dd($product->option_sets);
            foreach($product->option_sets as $option_set) {
                foreach($option_set->options as $option) {
                    $option_sets[$option->id] = $option->value;
                }
            }
            $images = [];
            $images[918434673] = 'C:\Users\Admin\Desktop\imagew\a.png';
            $images[918435375] = 'C:\Users\Admin\Desktop\imagew\b.png';
            $images[918427952] = 'C:\Users\Admin\Desktop\imagew\c.png';
            foreach($product->images as $image) {
                $images[$image->id] = $image->src;
            }
            // dd($images);
            $i = 1;
            foreach ($product->variants as $variant) {
                $sale_price = $variant->price;
                $regular_price = $variant->compare_at_price;
                $image = $images[$variant->image_id];
                $style = $option_sets[$variant->option1];
                $color = $option_sets[$variant->option2];
                $size = $option_sets[$variant->option3];

                $product = new WooProduct;
                $product->ProductName = $product_name;
                // $product->Description = $product_des;
                $product->RegularPrice = $regular_price;
                $product->SalePrice = $sale_price;
                $product->Image = $image;
                $product->Style = $style;
                $product->Color = $color;
                $product->Size = $size;
                if($i == 1) {
                    $product->Description = $product_des;
                }

                $product->save();
                $i++;
            }
           
        }
        curl_close($curlSession);
        dd('success');
});

Route::get('/new-test', function() {
    // $exif = exif_read_data('http://localhost/Laravel/Training03/public/test3.jpg', 0, true);
    $exif = exif_read_data('http://localhost/Laravel/Training03/public/test6.jpg');
    echo "test2.jpg:<br />\n";
    echo '<pre>';
    print_r($exif);die;
    echo '</pre>';
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {
            echo "$key.$name: $val<br />\n";
        }
    }


});

Route::get('/crawl-json', function() { 
    ini_set('max_execution_time', 15000);

    for($a = 41; $a <= 50; $a++) {
        $curlSession2 = curl_init();
        // curl_setopt($curlSession2, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/product.json?handle=hawaiian-identifier-t-shirt-special-edition');
        curl_setopt($curlSession2, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/products_v2.json?sort_field=name&sort_direction=asc&limit=12&page='.$a.'&collection_ids=86735081421');
        curl_setopt($curlSession2, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession2, CURLOPT_RETURNTRANSFER, true);
    
        $jsonData = json_decode(curl_exec($curlSession2));
        // echo '<pre>';
        // print_r($jsonData); die;
        // echo '</pre>';
        $products = $jsonData->products;
    
        foreach($products as $product) {
            $handle = $product->handle;
    
            $curlSession = curl_init();
            curl_setopt($curlSession, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/product.json?handle=' . $handle);
            // curl_setopt($curlSession, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/product.json?handle=hawaii-state-hibiscus-pink-polynesian-t-shirt-floral');
            curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    
            $jsonData = json_decode(curl_exec($curlSession));
            // echo '<pre>';
            // print_r($jsonData);die;
            // echo '</pre>';
            $options = [];
            foreach($jsonData->configurable_options as $key => $option) {
                foreach($option->values as $key2 => $value) {   
                    $index = $key +1 ;
                    $options['option' . $index][$value->value_index] = $value->label;
                }
            }
            $description = $jsonData->description;
            $images = '';
            foreach ($jsonData->media_gallery as $value) {
                $images .= $value->image .',';
            }
            if($images != '') 
                $images = substr($images, 0, strlen($images) - 1);
            $tags = $jsonData->tags;
            $name = $jsonData->name;
            $i = 0;
            foreach($jsonData->configurable_children as  $product) {
                $p = new App\Models\WooProduct;
                if($i == 0) {   
                    $p->Description = $description;
                    $p->Image = $images;
                }
                $p->RegularPrice = $product->compare_at_price;
                $p->SalePrice = $product->price;
                $p->Style = $options['option1'][$product->option1];
                $p->Size = $options['option2'][$product->option2];
                if(count($options['option3']) > 1)
                    $p->Color = $options['option3'][$product->option3];
                $p->ProductName = $name;
                $p->Tags = $tags;
                $p->save();
                $i++;
            }
    
            curl_close($curlSession);
        }
        echo $a . '<br>';
        curl_close($curlSession2);

    }

    dd('success');
});

Route::get('/crawl-json2', function() { 
    ini_set('max_execution_time', 15000);

    // for($a = 41; $a <= 50; $a++) {
        $curlSession2 = curl_init();
        // curl_setopt($curlSession2, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/product.json?handle=hawaiian-identifier-t-shirt-special-edition');
        curl_setopt($curlSession2, CURLOPT_URL, 'https://www.yaloboss.com/api/catalog/products_v2.json?sort_field=id&sort_direction=desc&limit=24&page=1&collection_ids=86735255079');
        curl_setopt($curlSession2, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession2, CURLOPT_RETURNTRANSFER, true);
    
        $jsonData = json_decode(curl_exec($curlSession2));
        // echo '<pre>';
        // print_r($jsonData); die;
        // echo '</pre>';
        $products = $jsonData->products;
        $i = 1;
        foreach($products as $product) {
            $handle = $product->handle;
    
            $curlSession = curl_init();
            curl_setopt($curlSession, CURLOPT_URL, 'https://www.yaloboss.com/api/catalog/product.json?handle=' . $handle);
            // curl_setopt($curlSession, CURLOPT_URL, 'https://www.alohawaii.co/api/catalog/product.json?handle=hawaii-state-hibiscus-pink-polynesian-t-shirt-floral');
            curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    
            $jsonData = json_decode(curl_exec($curlSession));
            // echo '<pre>';
            // print_r($jsonData);die;
            // echo '</pre>';
            
            $description = $jsonData->description;
            $images = '';
            foreach ($jsonData->media_gallery as $value) {
                $images .= $value->image .',';
            }
            if($images != '') 
                $images = substr($images, 0, strlen($images) - 1);
            $tags = $jsonData->tags;
            $name = $jsonData->name;
            
            $sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];
            foreach($sizes as $key => $size) {
                if($key == 0) {
                    $p = new WooProduct;
                    $p->ProductName = $name;
                    $p->RegularPrice = 49.95;
                    $p->SalePrice = 39.95;
                    $p->Size = $size;
                    $p->Description = $description;
                    $p->Image = $images;
                    // $p->Color = 'Colorful';
                    $p->Tags = $tags;
                    $p->save();
                }
                else if( $key > 0) {
                    $p = new WooProduct;
                    $p->ProductName = $name;
                    $p->RegularPrice = 49.95;
                    $p->SalePrice = 39.95;
                    $p->Size = $size;
                    // $p->Color = 'Colorful';
                    $p->Tags = $tags;
                    $p->save();
                }
            }
            curl_close($curlSession);
        }
        curl_close($curlSession2);
        echo $i;

    // }

    dd('success');
});


Route::get('/tool-crawl', function() {
    return View('crawl.index');
});

Route::post('crawl-data', function(Request $request){
    $url = $request->url;
    
    $page = (int) $request->number_page;
    $name = $request->name;
    $export = new GOPO;
    $data = $export->scrape($url, $page);
    return Excel::download(new ProductPExport($data), $name .'.csv');
})->name('crawl');


Route::get('/hash', function() {
    //Key
    $key = 'SuperSecretKey';

    //To Encrypt:
    $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, 'I want to encrypt this', MCRYPT_MODE_ECB);
    dd($encrypted);
    //To Decrypt:
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_ECB);
}); 