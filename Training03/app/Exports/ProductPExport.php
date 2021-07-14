<?php

namespace App\Exports;

use App\Models\WooProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductPExport implements FromArray, WithHeadings
{
    public $data;
    public function __construct($data)
    {
        
        $this->data = $data;
    }


    public function array(): array
    {
        return (array)$this->data;
    }

    public function headings(): array
    {
        return [
            // 'ID', 
            // 'Type', 
            // 'SKU', 
            // 'Name', 
            // 'Published',
            // 'Is featured?',
            // 'Visibility in catalog',
            // 'Short description',
            // 'Description',
            // 'Date sale price starts',
            // 'Date sale price ends',
            // 'Tax status',
            // 'Tax class',
            // 'In stock?',
            // 'Stock',
            // 'Low stock amount',
            // 'Backorders allowed?',
            // 'Sold individually?',
            // 'Weight (kg)',
            // 'Length (cm)',
            // 'Width (cm)',
            // 'Height (cm)',
            // 'Allow customer reviews?',
            // 'Purchase note',
            // 'Sale price', //??????
            // 'Regular price',
            // 'Categories',
            // 'Tags',
            // 'Shipping class',
            // 'Images',
            // 'Download limit',
            // 'Download expiry days',
            // 'Parent',
            // 'Grouped products',
            // 'Upsells',
            // 'Cross-sells',
            // 'External URl',
            // 'Button text',
            // 'Position',
            // 'Swatches Attributes',
            'ID',
            'Product Name',
            'Description',
            'Short Description',
            'Regular Price',
            'Sale Price', 
            'Image',
            'Style',
            'Color',
            'Size', 
            'Tags',
            'ProductCode'
        ];
    }

}
