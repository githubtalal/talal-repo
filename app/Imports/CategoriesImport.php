<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoriesImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $dataInRow = [
                'name' => $row['name'],
                'active' => $row['active'] ?? 1,
                'image_settings' => [
                    'font_color' => $row['font_color'] ?? '#ffffff',
                    'background_color' => $row['background_color'] ?? '#000000',
                    'opacity' => $row['opacity'] ?? 0.5,
                    'status' => $row['image_settings_status'] ?? 0,
                ]
            ];

            $category = Category::where('id', $row['id'])->first();

            if ($category) {
                $category->update($dataInRow);
            } else {
                Category::create($dataInRow);
            }
        }
    }
}
