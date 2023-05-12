<?php

namespace App;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;

class Invoice
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function prepareInvoice()
    {
        $data = [
            'invoiceId' => $this->order->id,
            'date' => now()->toDateString(),
            'store_name' => $this->order->store->name,
            'store_type' => $this->order->store->type,
            'customer_name' =>  $this->order->first_name . ' ' . $this->order->last_name,
            'customer_phone_number' => $this->order->phone_number,
            'products' => $this->order->items()->get(),
            'subtotal' => $this->order->total
        ];

        $html = view('newDashboard.invoice', ['data' => $data])->render();

        $arabicHtml = transform_content_to_arabic($html);

        $pdf = PDF::loadHTML($arabicHtml);

        $fileName =  'invoices/invoice_' . $this->order->id . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());

        return $fileName;
    }
}
