<?php

namespace App\Scraper;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use App\Models\WooProduct;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;

class GOPO
{

    public function scrape43() {

        ini_set('max_execution_time', 5000);
        for($a = 7; $a <= 8; $a++) {
            $url = 'https://thehappywood.com/product-category/clothing/hawaii-shirt//page/'. $a.'/?fbclid=IwAR0LGNZmwbmt_BugyqsVwWzA3Vd6aAVY3zztElEVy6lZxTnulsRd5tTofMg';
            $client = new Client();
            $crawler = $client->request('GET', $url); 
            $crawler2 = $crawler->filter('p.product-title > a')->each(
                function(Crawler $node)  {
                    $client = new Client();
                    $url = $node->attr('href');
                    $crawler = $client->request('GET', $url);
                    $image = '';
                    // $products = $crawler->filter('.variations_form.cart')->attr('data-product_variations');
                    // $products = json_decode($products);
                    // echo '<pre>';
                    // print_r($products);die;
                    // echo '</pre>';
                    $image = '';
                    $crawler->filter('.woocommerce-product-gallery__image.slide > a')->each(
                        function(Crawler $node) use (&$image)  {
                            $img =  $node->attr('href') . ',';
                            if($img != ',')
                                $image .= $img;
                        } 
                    );  
                    $image = substr($image, 0, strlen($image) - 1 );
                    $title = $crawler->filter('h1.product-title')->text();
                    $description = $crawler->filter('.woocommerce-Tabs-panel')->html();
                    $tags = \str_replace(' ', ', ', $title);
                    $i = 0;
                    $sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];
                    $prices['S'] = 31.95;
                    $prices['M'] = 33.95;
                    $prices['L'] = 33.95;
                    $prices['XL'] = 33.95;
                    $prices['2XL'] = 35.95;
                    $prices['3XL'] = 35.95;
                    $prices['4XL'] = 36.95;
                    $prices['5XL'] = 36.95;
                    foreach($sizes as $key => $size) {
                        if($key == 0) {
                            $p = new WooProduct;
                            $p->ProductName = $title;
                            $p->RegularPrice = $prices[$size];
                            $p->Size = $size;
                            $p->Description = $description;
                            $p->Image = $image;
                            $p->Tags = $tags;
                            $p->save();
                        }
                        else if( $key > 0) {
                            $p = new WooProduct;
                            $p->ProductName = $title;
                            $p->RegularPrice = $prices[$size];
                            $p->Size = $size;
                            $p->Tags = $tags;
                            $p->save();
                        }
                    }
                }
            );
        }
        
        dd('het');
    }
    public function scrape99() {
        // $client = new Client();
        // $url = 'https://gopostore.com/product/dragon-lover-polo-shirt-yhhh2705107psh/';
        // $crawler = $client->request('GET', $url);
        // $products = $crawler->filter('.variations_form.cart')->attr('data-product_variations');
        // $products = json_decode($products);
        // echo '<pre>';
        // print_r($products);die;
        // echo '</pre>';

        ini_set('max_execution_time', 5000);
        for($a = 17; $a <= 22; $a++) {
            $url = 'https://gopostore.com/collections/hawaiian-shirt/page/' .$a;
            $client = new Client();
            $crawler = $client->request('GET', $url); 
           
            $crawler2 = $crawler->filter('p.name.product-title.woocommerce-loop-product__title > a')->each(
                function(Crawler $node)  {
                    $client = new Client();
                    $url = $node->attr('href');
                    $crawler = $client->request('GET', $url);
                    $image = '';
                    $products = $crawler->filter('.variations_form.cart')->attr('data-product_variations');
                    $products = json_decode($products);
                    // echo '<pre>';
                    // print_r($products);die;
                    // echo '</pre>';
                    $image = '';
                    // dd($crawler->filter('.flickity-slider'));
                    $crawler->filter('.woocommerce-product-gallery__image.slide > a')->each(
                        function(Crawler $node) use (&$image)  {
                            $img =  $node->attr('href') . ',';
                            if($img != ',')
                                $image .= $img;
                        } 
                    );  
                    $image = substr($image, 0, strlen($image) - 1 );
                    // if($image == '') {
                    //     $image = 'https:' .$crawler->filter('.product-single__photo-wrapper.small--hide.js img.feature-row__image')->attr('src');
                    // }
                    $title = $crawler->filter('h1.product-title')->text();
                    $description = $crawler->filter('.woocommerce-Tabs-panel')->html();
                    // $tags = implode(",", $products->tags);
                    $i = 0;
                    foreach($products as $product) {
                        $i++;
                        $p = new WooProduct;
                        if($i == 1 ) {
                            $p->Description = $description;
                            $p->Image = $image;

                        }
                        $p->ProductName = $title;
                        // $p->Tags = $tags;
                        $p->SalePrice = (float) $product->display_price;
                        $p->RegularPrice = (float) $product->display_regular_price;
                        $p->Style = '' ;
                        $p->Color = '';
                        $p->Size = \strtoupper( $product->attributes->attribute_pa_size);
                        $p->save();
                        
                    }
                }
            );
        }
        
        dd('het');
    }
    public function scrape($url_site, $page) {
        ini_set('max_execution_time', 14950000);
        $data = [];
        $i = 1;
        for($a = 1; $a <= $page; $a++) {
            $client = new Client();
            
            $url = $url_site. 'page/' . $a;
            $crawler = $client->request('GET', $url);
            $crawler->filter('.col-md-12.az-content > .az-product-item>.az-overflow')->each(
                function(Crawler $node) use (&$i, &$data)  {
                    $client = new Client();
                    $url = $node->filter('a')->attr('href');
                    
                    $crawler = $client->request('GET', $url);
                    $image = '';
                    $crawler->filter('.woocommerce-product-gallery__image > a')->each(
                        function(Crawler $node) use (&$image)  {
                            $img =  $node->attr('href') . ',';
                            if($img != ',')
                                $image .= $img;
                        } 
                    );  
                    $image = substr($image, 0, strlen($image) - 1 );
                    $title = $crawler->filter('h1.product_title.entry-title')->text();
                    $price_html = $crawler->filter('p.price')->text();
                    $price_arr = explode(" ",$price_html);
                    $price = (float) str_replace('.', '', $price_arr[0]);
                    // $short_description = '';
                    // if($url != 'https://vuhoangtelecom.vn/san-pham/o-cung-internal-ssd-128gb-hikvision-hs-ssd-e100std-128g/')
                    $short_description = $crawler->filter('.woocommerce-product-details__short-description')->html();
                    // $description = $crawler->filter('.content-tabs.tabs-mota.loadmore')->html();
                  
                    $tags  = str_replace(' ', ', ', $title);
                    try {
                        $info_price = $crawler->filter('ul.info-price li')->last()->text();
                        $arr = explode(' ', $info_price);
                        $regular_price = (float) str_replace('.', '', $arr[3]);
                    } catch (\Throwable $th) {
                        $regular_price  = '';
                    }
                    $product_code = $crawler->filter('.info-attribute-product > span')->text();
                    $t = 0;
                    $des = '';
                    $crawler->filter('.description-product .row .col-md-12 .title-tabs')->each(function (Crawler $n) use (&$t, &$des){
                        if($t <= 2) {
                            $des .= $n->html();
                        }
                        $t++;
                    });
                    $data[$i][] = $i;
                    $data[$i][] = $title;
                    $data[$i][] = $des;
                    $data[$i][] = $short_description;
                    $data[$i][] = $regular_price;
                    $data[$i][] = $price;
                    $data[$i][] = $image;
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] =$tags;
                    $data[$i][] =$product_code;
                    $i++;
                    return $data;
                }
            );
        }
        return $data;
        dd('xong');

        for($a = 17; $a <= 22; $a++) {
            $url = 'https://gopostore.com/collections/hawaiian-shirt/page/' .$a;
            $client = new Client();
            $crawler = $client->request('GET', $url); 
           
            $crawler2 = $crawler->filter('p.name.product-title.woocommerce-loop-product__title > a')->each(
                function(Crawler $node)  {
                    $client = new Client();
                    $url = $node->attr('href');
                    $crawler = $client->request('GET', $url);
                    $image = '';
                    $products = $crawler->filter('.variations_form.cart')->attr('data-product_variations');
                    $products = json_decode($products);
                    // echo '<pre>';
                    // print_r($products);die;
                    // echo '</pre>';
                    $image = '';
                    // dd($crawler->filter('.flickity-slider'));
                    $crawler->filter('.woocommerce-product-gallery__image.slide > a')->each(
                        function(Crawler $node) use (&$image)  {
                            $img =  $node->attr('href') . ',';
                            if($img != ',')
                                $image .= $img;
                        } 
                    );  
                    $image = substr($image, 0, strlen($image) - 1 );
                    // if($image == '') {
                    //     $image = 'https:' .$crawler->filter('.product-single__photo-wrapper.small--hide.js img.feature-row__image')->attr('src');
                    // }
                    $title = $crawler->filter('h1.product-title')->text();
                    $description = $crawler->filter('.woocommerce-Tabs-panel')->html();
                    // $tags = implode(",", $products->tags);
                    $i = 0;
                    foreach($products as $product) {
                        $i++;
                        $p = new WooProduct;
                        if($i == 1 ) {
                            $p->Description = $description;
                            $p->Image = $image;

                        }
                        $p->ProductName = $title;
                        // $p->Tags = $tags;
                        $p->SalePrice = (float) $product->display_price;
                        $p->RegularPrice = (float) $product->display_regular_price;
                        $p->Style = '' ;
                        $p->Color = '';
                        $p->Size = \strtoupper( $product->attributes->attribute_pa_size);
                        $p->save();
                        
                    }
                }
            );
        }
        
        dd('het');
    }


    public function scrape7()
    {
        
        // $url = 'https://www.veterannations.com/collections/army-tees';
        // $client = new Client();
        // $url = 'https://www.veterannations.com/products/us-army-e-5-sergeant-e5-sgt-noncommissioned-officer-ranks-men-front-shirt-us-army-rank';
        // $crawler = $client->request('GET', $url);
        // $image = '';
        // $crawler->filter('.product-gallery__size-limiter .aspect-ratio .product-gallery__image')->each(
        //     function(Crawler $node) use (&$image)  {
        //         $img =  'https:' .$node->attr('data-zoom') . ',';
        //         if($img != ',')
        //             $image .= $img;
        //     } 
        // ); 
        // $image = substr($image, 0, strlen($image) - 1 );
        ini_set('max_execution_time', 5000);
        for($a = 7; $a <= 9; $a++) {

        
        $url = 'https://www.veterannations.com/collections/air-force-t-shirt?page='.$a;
        $client = new Client();
        $crawler = $client->request('GET', $url); 
        $crawler2 = $crawler->filter('.product-item.product-item--vertical>a')->each(
            function(Crawler $node)  {
                $client = new Client();

                $url = 'https://www.veterannations.com' .$node->attr('href');
                $crawler = $client->request('GET', $url);

                $image = '';
                $crawler->filter('.product-gallery__size-limiter .aspect-ratio .product-gallery__image')->each(
                    function(Crawler $node) use (&$image)  {
                        $img =  'https:' .$node->attr('data-zoom') . ',';
                        if($img != ',')
                            $image .= $img;
                    } 
                ); 
                $image = substr($image, 0, strlen($image) - 1 );
                $des = $crawler->filter('.product-block-list__item.product-block-list__item--description .rte.text--pull')->html();
                // dd($crawler->filter('.product-form__variants .select-wrapper.select-wrapper--primary select>option'));
                $product_name = $crawler->filter('.product-block-list__item.product-block-list__item--info .product-meta>h1')->text();
                $crawler->filter('.product-form__variants .no-js .select-wrapper.select-wrapper--primary select option')->each(
                    function(Crawler $node, $i = 0) use (&$des, &$product_name, $image) {
                        $i++;
                        $p = new WooProduct;
                        if($i == 1) {
                            $p->Image = $image;
                            $p->Description = $des;
                        }
                        $str =  $node->text();
                        $arr = explode('/', $str);
                        if(count($arr) == 3) {
                            $style = trim($arr[0]);
                            $color = trim($arr[1]);
                            $size_price = explode('-', trim($arr[2]));
                            $size = trim($size_price[0]);
                        }
                        else if( count($arr) == 2) {
                            $style = '';
                            $color = trim($arr[0]);
                            $size_price = explode('-', trim($arr[1]));
                            $size = trim($size_price[0]);
                        }
                        else if(count($arr) == 4) {
                            $style = '';
                            $color = $arr[1]. '/' . $arr[2];;
                            $size_price = explode('-', trim($arr[3]));
                            $size = trim($size_price[0]);
                        }
                        
                        if(isset($size_price[1]))
                            $price = substr($size_price[1], 2, strlen($size_price[1]));
                        else 
                            $price = substr($size_price[0], 1, strlen($size_price[0]));

                        $p->ProductName = $product_name;
                        $p->RegularPrice = (double)$price;
                        $p->Style = $style ;
                        $p->Color = $color;
                        $p->Size = $size;
                        $p->save();
                        echo $price .'<br>';
                        
                    } 
                    );
            }

        );
        }
        dd('het');
    }
    public function scrape77()
    {
        ini_set('max_execution_time', 125000);
        
        // $url = 'https://www.veterannations.com/collections/army-tees';
        // $client = new Client();
        // $url = 'https://myfamilytee.com/collections/when-i-grow-up-collection/products/when-i-grow-up-i-want-to-be-just-like-dad?variant=5015576193';
        // $crawler = $client->request('GET', $url);
        
        // $image = '';
        // $crawler->filter('.thumbs.clearfix .image.span2 a')->each(
        //     function(Crawler $node) use (&$image)  {
        //         $img =  'https:' .$node->attr('href') . ',';
        //         if($img != ',')
        //             $image .= $img;
        //     } 
        // ); 
        // $image = substr($image, 0, strlen($image) - 1 );
        // for($a = 7; $a <= 9; $a++) {
        
            $url = 'https://myfamilytee.com/collections/not-spoiled-collection';
            $client = new Client();
            $crawler = $client->request('GET', $url); 
            $crawler2 = $crawler->filter('.details >a')->each(
                function(Crawler $node)  {
                    $client = new Client();

                    $url = 'https://myfamilytee.com' .$node->attr('href');
                    $crawler = $client->request('GET', $url);
                    $image = '';
                    $crawler->filter('.image.span2 a')->each(
                        function(Crawler $node) use (&$image)  {
                            $img =  'https:' .$node->attr('href') . ',';
                            if($img != ',')
                                $image .= $img;
                        } 
                    ); 
                    $image = substr($image, 0, strlen($image) - 1 );

                    // $des = $crawler->filter('.description')->html();
                    $product_name = $crawler->filter('h1.title')->text();
                    $des = '<strong>'.$product_name.'!</strong><br><br><strong>**Not Sold in Stores **</strong> 100% Custom Designed &amp; Printed in the USA, and we Ship Worldwide! Satisfaction Guaranteed or Your Money Back!<br><br>Make sure to share on Facebook and tag your Family &amp; Friends!<br><br><strong>Simply Select Your Shirt Style &amp; Shirt Color then Click "Add to Cart".</strong><br><br>';
                    $crawler->filter('#product-select option')->each(
                        function(Crawler $node, $i = 0) use (&$des, &$product_name, $image) {
                            $i++;
                            $p = new WooProduct;
                            if($i == 1) {
                                $p->Image = $image;
                                $p->Description = $des;
                            }
                            $str =  $node->text();
                            $arr = explode('/', $str);
                            if(count($arr) == 3) {
                                $style = trim($arr[0]);
                                $size = trim($arr[1]);
                                $color_price = explode('-', trim($arr[2]));
                                $color = trim($color_price[0]);
                            }
                           
                            // else if( count($arr) == 2) {
                            //     $style = '';
                            //     $color = trim($arr[0]);
                            //     $size_price = explode('-', trim($arr[1]));
                            //     $size = trim($size_price[0]);
                            // }
                            // else if(count($arr) == 4) {
                            //     $style = '';
                            //     $color = $arr[1]. '/' . $arr[2];;
                            //     $size_price = explode('-', trim($arr[3]));
                            //     $size = trim($size_price[0]);
                            // }
                            
                            // if(isset($size_price[1]))
                            $price = substr($color_price[1], 2, strlen($color_price[1]));
                            // else 
                            //     $price = substr($size_price[0], 1, strlen($size_price[0]));
                            $p->Tags = str_replace(' ' , ', ' , $product_name);
                            $p->ProductName = $product_name;
                            if((double) $price == 19.95 || (double) $price == 24.95 )
                                $p->RegularPrice = 29.95;
                            else {
                                $p->RegularPrice = 49.95;
                            }
                            $p->SalePrice = (double)$price;
                            $p->Style = $style ;
                            $p->Color = $color;
                            $p->Size = $size;
                            $p->save();
                            echo $price .'<br>';
                            
                        } 
                        );
                }

            );
        // }
        dd('het');
    }

    public function scrape5()
    {
        
        ini_set('max_execution_time', 5000);
        // for($a = 41; $a <= 42; $a++) {

        
        $url = 'https://myqueenstyle.com/products?handler=nfl-hawaiian-shirts&page=1&per-page=24';
        $client = new Client();
        $crawler = $client->request('GET', $url); 
        $crawler2 = $crawler->filter('ul.classify-ul.four-col li div a')->each(
            function(Crawler $node)  {
                $client = new Client();
                $url = 'https://myqueenstyle.com' .$node->attr('href');
                $crawler = $client->request('GET', $url);
                $image = '';
                $crawler->filter('.swiper-slide.swiper-slide-min div img')->each(
                    function(Crawler $node) use (&$image)  {
                        $img =  $node->attr('src') . ',';
                        if($img != ',')
                            $image .= $img;
                    } 
                ); 
                $image = substr($image, 0, strlen($image) - 1 );
                $des = $crawler->filter('.product-details')->html();
                $price = (double) $crawler->filter('p.price span.pay-price')->attr('data-price');
                $regular_price = (double) $crawler->filter('.orig-price.sl-subhead-title')->attr('data-price');
                $product_name = $crawler->filter('h3.title.sl-main-title')->text();
                $crawler3 = $crawler->filter('.types-item.J-Attribute select.J-Value.sl-subhead-title option')->each(
                    function(Crawler $node, $i = 0) use (&$des, &$product_name, $image, $price, $regular_price) {
                        $i++;
                        $p = new WooProduct;
                        if($i == 2) {
                            $p->Image = $image;
                            $p->Description = $des;
                            $p->ProductName = $product_name;
                            $p->SalePrice = $price;
                            $p->Color = 'same_as_photo' ;
                            $p->Size = trim($node->text());
                            $p->RegularPrice = $regular_price;
                            $p->save();
                        }
                        else if( $i > 2) {
                            $p->ProductName = $product_name;
                            $p->SalePrice = (double)$price;
                            $p->Color = 'same_as_photo' ;
                            $p->Size = trim($node->text());
                            $p->RegularPrice = $regular_price;
                            $p->save();
                        }   
                    } 
                );
                die;
            }

        );
        // }
        dd('het');
        $url = 'https://www.veterannations.com/products/army-veteran-flag-t-shirt';
        
        
    }

    public function scrape2()
    {
            $url = 'https://www.veterannations.com/collections/navy-hooded-blanket?page=4';
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('.product-item__info-inner a')->each(
                function( Crawler $node, $i = 0) {
                    $client = new Client();
                    $link = 'https://www.veterannations.com' . $node->attr('href');
                    $url = $link;
                    $crawler = $client->request('GET', $url);
                    $name = $crawler->filter('.product-block-list__item.product-block-list__item--info .product-meta h1.product-meta__title')->text();
                    $price = $crawler->filter('.price')->text();
                    $price = (float)substr($price, 1, strlen($price));
                    $image = '';
                    $crawler->filter('.product-gallery__size-limiter img')->each(
                        function(Crawler $n) use (&$image) {
                            $img =  'https:' .$n->attr('data-zoom') . ',';
                            if($img != ',')
                                $image .= $img;
                        }
                    );
                    $image = substr($image, 0, strlen($image) - 1 );
                    $des = $crawler->filter('.rte.text--pull')->html();    
                    $p = new WooProduct;
                    $p->ProductName = $name;
                    $p->RegularPrice = $price;
                    $p->Size = 'One Size';
                    $p->Description = $des;
                    $p->Image = $image;
                    $p->save();

                    $q = new WooProduct;
                    $q->ProductName = $name;
                    $q->RegularPrice = $price;
                    $q->Size = 'One Size';
                    // $q->Description = $des;
                    // $q->Image = $image;
                    $q->save();
                    
                }
            );

    }
    public function scrape9()
    {
        // for($a = 1; $a <= 2; $a++) {
            $url = 'https://vio-store.com/product-category/shop-by-products/shorts/page/7';
            $client = new Client();
            $crawler = $client->request('GET', $url);
        
            $crawler->filter('p.name.product-title.woocommerce-loop-product__title a')->each(
                function( Crawler $node, $i = 0) {
                    $client = new Client();
                    $link =  $node->attr('href');
                    $url = $link;
                    $crawler = $client->request('GET', $url);
                      
                    $name = $crawler->filter('h1.product-title')->text();
                    $price = $crawler->filter('span.woocommerce-Price-amount.amount bdi')->text();
                    $price = (float) \substr($price, 1, strlen($price));
                    $description = $crawler->filter('#tab-description')->html();
                    $image = '';
                    $tags = \str_replace(' ', ', ', $name);
                    $crawler->filter('.woocommerce-product-gallery__image.slide a img')->each(
                        function(Crawler $n) use (&$image) {
                            $img =  $n->attr('src') . ',';
                            if($img != ',')
                                $image .= $img;
                        }
                    );
                    $image = substr($image, 0, strlen($image) - 1 );
                    $sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];
                    foreach($sizes as $key => $size) {
                        if($key == 0) {
                            $p = new WooProduct;
                            $p->ProductName = $name;
                            $p->RegularPrice = 39.95;
                            $p->Size = $size;
                            $p->Description = $description;
                            $p->Image = $image;
                            $p->Tags = $tags;
                            $p->save();
                        }
                        else if( $key > 0) {
                            $p = new WooProduct;
                            $p->ProductName = $name;
                            $p->RegularPrice = 39.95;
                            $p->Size = $size;
                            $p->Tags = $tags;
                            $p->save();
                        }
                    }
                }
            );
        // }
        dd('sd');
    }
    
    public function scrape123()
    {
        ini_set('max_execution_time', 5000);
        for($a = 87; $a <= 90; $a++) {
            $url = 'https://rageontee.com/collections/hawaiian-shirt?page='. $a;
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('a.grid-view-item__link.grid-view-item__image-container')->each(
                function( Crawler $node, $i = 0) {
                    $client = new Client();
                    $link =  'https://rageontee.com' . $node->attr('href');
                    $url = $link;
                    $crawler = $client->request('GET', $url);
                      
                    $name = $crawler->filter('h1.product-single__title.heading')->text();
                    $r_price = $crawler->filter('#ComparePrice-product-template')->text();
                    $r_price = (float) \substr($r_price, 1, strlen($r_price));
                    $s_price = (float) $crawler->filter('#ProductPrice-product-template')->attr('content');

                    $description = $crawler->filter('.product-description')->html();
                    $image = '';
                    $tags = \str_replace(' ', ', ', $name);
                    $crawler->filter('noscript img.product-featured-img')->each(
                        function(Crawler $n) use (&$image) {
                            $img = 'http:'. $n->attr('src') . ',';
                            if($img != ',')
                                $image .= $img;
                        }
                    );
                    $image = substr($image, 0, strlen($image) - 1 );
                    $sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];
                    foreach($sizes as $key => $size) {
                        if($key == 0) {
                            $p = new WooProduct;
                            $p->ProductName = $name;
                            $p->RegularPrice = $r_price;
                            $p->SalePrice = $s_price;
                            $p->Size = $size;
                            $p->Description = $description;
                            $p->Image = $image;
                            $p->Color = 'Colorful';
                            $p->Tags = $tags;
                            $p->save();
                        }
                        else if( $key > 0) {
                            $p = new WooProduct;
                            $p->ProductName = $name;
                            $p->RegularPrice = $r_price;
                            $p->SalePrice = $s_price;
                            $p->Size = $size;
                            $p->Color = 'Colorful';
                            $p->Tags = $tags;
                            $p->save();
                        }
                    }
                }
            );
        }
        dd('xong');
    }



    public function scrape3D()
    {
        $des = '<div class="product-detail-content">
        <div class="sellingpoints-new">
            <h3 class="sellingpoints-title">Selling Points</h3>
            <ul class="sellingpoints-ul">
                <li>
                    <span class="detail-num litb">1.</span>
                    <span class="detail-info">
                        <span class="selling-ponints-description">
                            <b>Neckline:</b>
                            <b>Round Neck</b>
                        </span>
                    </span>
                </li>
                <li>
                    <span class="detail-num litb">2.</span>
                    <span class="detail-info">
                        <span class="selling-ponints-description">
                            <b>Sleeve Length:</b>
                            <b>Short Sleeve</b>
                        </span>
                    </span>
                </li>
                <li>
                    <span class="detail-num litb">3.</span>
                    <span class="detail-info">
                        <span class="selling-ponints-description">
                            <b>Season:</b>
                            <b>Summer</b>
                        </span>
                    </span>
                </li>
                <li>
                    <span class="detail-num litb">4.</span>
                    <span class="detail-info">
                        <span class="selling-ponints-description">
                            <b>Special Size:</b>
                            <b>Plus Size</b>
                            <b> - </b>Special size for Large size or big size people.
                        </span>
                    </span>
                </li>
            </ul>
        </div>
        <div class="specifications">
            <div class="sub-title">Specifications</div>
            <div class="specTitle">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Gender</td>
                            <td>
                                <span>Men\'s</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Style</td>
                            <td>
                                <span>Cool</span>
                                , <span>Exaggerated</span> , <span>Streetwear</span> , <span>Fashion</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Occasion</td>
                            <td>
                                <span>Casual</span>
                                , <span>Daily</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tops Type</td>
                            <td>
                                <span>T shirt</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Neckline</td>
                            <td>
                                <span>Round Neck</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Fabric</td>
                            <td>
                                <span>Polyester</span>
                                , <span>Spandex</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sleeve Length</td>
                            <td>
                                <span>Short Sleeve</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Elasticity</td>
                            <td>
                                <span>Stretchy</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Pattern</td>
                            <td>
                                <span>Optical Illusion</span>
                                , <span>Graphic</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Season</td>
                            <td>
                                <span>Summer</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Special Size</td>
                            <td>
                                <span>Plus Size</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Fit Type</td>
                            <td>
                                <span>Regular Fit</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Print Type</td>
                            <td>
                                <span>3D Print</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Production mode</td>
                            <td>
                                <span>External procurement</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="photos_block" class="photosblock desc-tab-block" data-lang="Photos">
            <div class="sub-title">Photos</div>
            <div class="detail-photowrap">
                <div class="photo-item" style="">
                    <picture
                        class=""
                        data-webp="https://li0.rightinthebox.com/webp_desc_image/202008/vxolhq1597052636729.jpg?com=1&amp;fmt=webp&amp;v=1"
                        data-origin="https://li0.rightinthebox.com/desc_image/202008/vxolhq1597052636729.jpg"
                        style=""
                    >
                        <source type="image/webp" srcset="https://li0.rightinthebox.com/webp_desc_image/202008/vxolhq1597052636729.jpg?com=1&amp;fmt=webp&amp;v=1" />
                        <img src="https://li0.rightinthebox.com/desc_image/202008/vxolhq1597052636729.jpg" />
                    </picture>
                </div>
                
            </div>
        </div>
        <div id="how_to_measure_block" class="highlight-block desc-tab-block" data-lang="How To Measure" data-block="new_how_to_measure">
            <div class="sub-title">How to Measure</div>
            <img src="https://li0.rightinthebox.com/images/dfp/fs-images/2021/e8a61b9005193e0acf40c4400e925ad5.jpg" style="max-width: 780px;" width="100%" />
        </div>
        <div id="size_chart_block" class="desc-tab-block" data-lang="Size Chart">
            <div class="onetalbewrap">
                <div class="size-tab-wrap first-wrap">
                    <h3 class="size-tab-title">Size Chart</h3>
                    <div class="switch-tab">
                        <div class="btn-change active" data-unit="cm">
                            <span class="btntext">CM</span>
                        </div>
                        <div class="btn-change" data-unit="inch" data-converto="cm">
                            <span class="btntext">INCH</span>
                        </div>
                    </div>
                </div>
    
                <div class="note"><b>ID:117649</b> Measured by <b>LightInTheBox</b>.Please Note: Listed size charts may vary according to different Size Chart IDs</div>
    
                <div class="sizechart-table-wrap">
                    <table class="sizechart-table">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Fit US Size</th>
                                <th>Fit UK Size</th>
                                <th>Fit EU Size</th>
                                <th>Bust</th>
                            </tr>
                        </thead>
                        <tbody class="unit-content">
                            <tr>
                                <td class="check-td" data-origin="S">S</td>
                                <td class="changeable" data-len="0" data-origin="36/38">36/38</td>
                                <td class="changeable" data-len="0" data-origin="36/38">36/38</td>
                                <td class="changeable" data-len="0" data-origin="46/48">46/48</td>
                                <td class="changeable" data-len="1" data-origin="94-99">94-99</td>
                            </tr>
                            <tr>
                                <td class="check-td" data-origin="M">M</td>
                                <td class="changeable" data-len="0" data-origin="40">40</td>
                                <td class="changeable" data-len="0" data-origin="40">40</td>
                                <td class="changeable" data-len="0" data-origin="50">50</td>
                                <td class="changeable" data-len="1" data-origin="100-105">100-105</td>
                            </tr>
                            <tr>
                                <td class="check-td" data-origin="L">L</td>
                                <td class="changeable" data-len="0" data-origin="42">42</td>
                                <td class="changeable" data-len="0" data-origin="42">42</td>
                                <td class="changeable" data-len="0" data-origin="52">52</td>
                                <td class="changeable" data-len="1" data-origin="106-111">106-111</td>
                            </tr>
                            <tr>
                                <td class="check-td" data-origin="XL">XL</td>
                                <td class="changeable" data-len="0" data-origin="44">44</td>
                                <td class="changeable" data-len="0" data-origin="44">44</td>
                                <td class="changeable" data-len="0" data-origin="54">54</td>
                                <td class="changeable" data-len="1" data-origin="112-118">112-118</td>
                            </tr>
                            <tr>
                                <td class="check-td" data-origin="XXL">XXL</td>
                                <td class="changeable" data-len="0" data-origin="46/48">46/48</td>
                                <td class="changeable" data-len="0" data-origin="46/48">46/48</td>
                                <td class="changeable" data-len="0" data-origin="56/58">56/58</td>
                                <td class="changeable" data-len="1" data-origin="119-126">119-126</td>
                            </tr>
                            <tr>
                                <td class="check-td" data-origin="3XL">3XL</td>
                                <td class="changeable" data-len="0" data-origin="48/50">48/50</td>
                                <td class="changeable" data-len="0" data-origin="48/50">48/50</td>
                                <td class="changeable" data-len="0" data-origin="58/60">58/60</td>
                                <td class="changeable" data-len="1" data-origin="127-134">127-134</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
            ';
        ini_set('max_execution_time', 125000);
        
        $url = 'https://m.lightinthebox.com/en/c/men-s-3d-t-shirts_63113';
        $client = new Client();
        // $url = 'https://m.lightinthebox.com/en/p/men-s-t-shirt-geometric-color-block-3d-print_p7204921.html?from_cid=63113&prm=1-2.2.1.1#header_anchor';
        $url = 'https://m.lightinthebox.com/en/list-ajax/63113/3?prm=1-2.2.58.16&is_module=0&page_size=48&view_type=grid&from=NewProduct&_=1626190598928&is_ajax=1';
        $crawler = $client->request('GET', $url);
        $data = [];
        $i = 1;
        $crawler->filter('.pro-imgwrap.litb a')->each(function(Crawler $node) use (&$data, &$i, $des) {
            $url = $node->attr('href') .'#header_anchor';
            $client = new Client();

            $crawler = $client->request('GET', $url);
            echo $crawler->filter('body')->html(). '<br>';

            $title_fake = $crawler->filter('.title-area.fold')->text();
            $title = substr($title_fake, 0,strrpos($title_fake, ' ') );
            echo $title . '<br>';

            $image = '';
            $crawler->filter('.product-main ul.slide-wrap li picture')->each(
                function(Crawler $node) use (&$image)  {
                    $img =  $node->attr('data-webp') . ',';
                    if($img != ',')
                        $image .= $img;
                } 
            ); 
            $image = substr($image, 0, strlen($image) - 1 );
            $sizes = ['XS','S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL'];

            $sale_price = $crawler->filter('span.price')->text();
            $sale_price = (float)substr($sale_price, 4);

            $regular_price = $crawler->filter('.original-price del span.price')->text();
            $regular_price = (float) substr($regular_price, 1);   
            $tags  = str_replace(' ', ', ', $title);

            foreach($sizes as $key => $size) {
                if($key == 0) {
                    $data[$i][] = $i;
                    $data[$i][] = $title;
                    $data[$i][] = $des;
                    $data[$i][] = '';
                    $data[$i][] = $regular_price;
                    $data[$i][] = $sale_price;
                    $data[$i][] = $image;
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] = $size;
                    $data[$i][] =$tags;
                    $data[$i][] = '';
                }
                else {
                    $data[$i][] = $title;
                    $data[$i][] = $i;
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] = $regular_price;
                    $data[$i][] = $sale_price;
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] = '';
                    $data[$i][] = $size;
                    $data[$i][] =$tags;
                    $data[$i][] = '';
                }
                $i++;
            }
            
        });
        return $data;
    }

} 