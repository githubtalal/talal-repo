<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'name',
            'active',
            'font_color',
            'background_color',
            'opacity',
            'image_settings_status'
        ];
    }

    public function collection()
    {
        $categories = Category::get();
        $items = new Collection();

        foreach ($categories as $category) {
            $settings = $category->image_settings;
            $item = new Collection([
                'id' => $category->id,
                'name' => $category->name,
                'active' => $category->active,
                'font_color' => $settings['font_color'] ?? '#282828',
                'background_color' => $settings['background_color'] ?? '#F5F5F5',
                'opacity' => $settings['opacity'] ?? 1,
                'image_settings_status' => $settings['status'] ?? 0
            ]);
            $items->push($item);
        }
        return $items;
    }
}
