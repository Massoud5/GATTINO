<?php
namespace App\Service\Pdf;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfService 
{
     private $domPdf;
     
     public function __construct(){

          
          $this->domPdf = new Dompdf();

          $pdfOptions = new Options();

          $pdfOptions->set('defaultFont', 'Garamond');

          $this->domPdf->setOptions($pdfOptions);
     }

     public function showPdfFile($html) {
          $this->domPdf->loadHtml($html);
          $this->domPdf->render();
          $this->domPdf->stream("facture-gattino.pdf", [
              'Attachment' => false,
              'Content-Type' => 'application/pdf'
          ]);

          exit(0);
     }

     public function DownloadPdfFile($html) {
          $this->domPdf->loadHtml($html);
          $this->domPdf->render();
          $this->domPdf->stream("facture-gattino.pdf", [
              'Attachment' => true,
              'Content-Type' => 'application/pdf'
          ]);
          return new Response;
     }
}