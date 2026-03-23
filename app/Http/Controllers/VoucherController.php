<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VoucherController extends Controller
{
    public function printVoucher()
    {
        $data = [
            'title' => "Voucher Print",
            'date' => date('m/d/Y')
        ];
        $pdf = Pdf::loadView('print', $data);

        return $pdf->stream('voucher.pdf');
    }
}
