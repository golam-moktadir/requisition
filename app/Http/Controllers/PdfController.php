<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller 
{
    public function viewPdf()
    {
        $data = [
            'foo' => 'Foo',
            'bar' => 'Bar',
            'bangla' => mb_convert_encoding('টেস্টিং pdf কন্টেন্ট', 'UTF-8', 'UTF-8'),
        ];

        // $pdf = PDF::loadView('mpdf.test1', $data);
        $pdf = PDF::chunkLoadView('<html-separator/>', 'mpdf.test1', $data);

        // $pdf->SetHeader('Document Title|Center Text|{PAGENO}');
        // $pdf->SetFooter('Document Title');

        return $pdf->stream('document.pdf');

        // To override this configuration on a per-file basis use the fourth parameter of the initializing call like this:
        // PDF::loadView('pdf', $data, [], [
        //     'title' => 'Another Title',
        //     'margin_top' => 0
        // ])->save($pdfFilePath);        
    }

    function test2() {

        // https://mpdf.github.io/fonts-languages/fonts-in-mpdf-6-x.html

        $mpdf = new \Mpdf\Mpdf();

        $mpdf->fontdata["frutiger"] = [
            'R' => "Frutiger-Normal.ttf",
            'I' => "FrutigerObl-Normal.ttf",
        ];        

        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';

        $mpdf->WriteHTML('<h1>Hello world!</h1> <p style="font-family: frutiger"> Hi,</p>');
        $mpdf->Output();

        die;
    }

}