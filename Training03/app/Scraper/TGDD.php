<?php

namespace App\Scraper;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;

class TGDD
{

    public function scrape()
    {
        $url = 'https://www.thegioididong.com/dtdd';

        $client = new Client();

        $crawler = $client->request('GET', $url);

    //    dd( $crawler->filter('ul.listproduct')) ;

        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) {
                
                $name = $node->filter('h3')->text();
                // dd($name);
                $price = $node->filter('strong.price')->text();
                $price = preg_replace('/\D/', '', $price);
                // dd($price);
                $halfStar = $node->filter('.item-rating .icon-star')->count(); 
                // $wholeStar = $node->filter('.icontgdd-ystar')->count(); dd($wholeStar);
                $wholeStar = $node->filter('.item-rating .icon-star-dark')->count();
                $rate = $wholeStar + 0.5 * $halfStar;

                $product = new Product;
                $product->name = $name;
                $product->price = $price;
                $product->rate = $rate;
                $product->save();
                
            }
        );
    }
}