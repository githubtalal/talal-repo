<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    private $dataExport;

    public function __construct($dataExport)
    {
        $this->dataExport = $dataExport;
    }

    public function collection()
    {
        return $this->dataExport->getAllData();
    }

    public function headings(): array
    {
        foreach ($this->dataExport->getColumns() as $column) {
            $headings[] = $column['name'];
        }

        return $headings;
    }
}
