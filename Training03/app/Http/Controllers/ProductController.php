<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Exports\ProductPExport;
use Maatwebsite\Excel\Facades\Excel;
use  App\Scraper\GOPO;

class ProductController extends Controller
{
    public function export() 
    {
        return Excel::download(new ProductExport, 'not-spoiled-collection.csv');
    }

    public function exportP() {
        
        $export = new GOPO;
        $data = $export->scrape3D();
        return Excel::download(new ProductPExport($data), 'camera-analog.csv');
    }
}
