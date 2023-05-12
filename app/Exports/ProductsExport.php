<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'name',
            'active',
            'price',
            'currency',
            'category_id',
            'type',
            'unit',
            'require_end_date',
        ];
    }

    public function collection()
    {
        $products = Product::select(
            'id',
            'name',
            'active',
            'price',
            'currency',
            'category_id',
            'type',
            'unit',
            'require_end_date',
        )->get();

        return $products;
    }
}
