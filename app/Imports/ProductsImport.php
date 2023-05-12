<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $imageName = 'products/' . uniqid();
            Storage::disk('public')->put($imageName, file_get_contents('https://returntofreedom.org/store/wp-content/uploads/shop-placeholder.png'));

            $dataInRow = [
                'name' => $row['name'],
                'active' => $row['active'] ?? 1,
                'price' => $row['price'],
                'currency' => $row['currency'] ?? 'SYP',
                'category_id' => $row['category_id'],
                'type' => $row['type'] ?? 'product',
                'unit' => $row['unit'] ?? 'quantity',
                'require_end_date' => $row['require_end_date'] ?? 0,
                'image_url' => $imageName
            ];

            $product = Product::where('id', $row['id'])->first();

            if ($product) {
                $product->update($dataInRow);
            } else {
                Product::create($dataInRow);
            }
        }
    }
}
