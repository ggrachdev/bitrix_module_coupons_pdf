<?php

namespace GGrach\CouponsPdf\Generator;

use Dompdf\Dompdf;

final class PdfGenerator {

    private string $pathFolderForGenerate;
    private string $nameFilePdf;

    public function __construct(string $pathFolderForGenerate, string $nameFilePdf) {
        $this->pathFolderForGenerate = $pathFolderForGenerate;
        $this->nameFilePdf = $nameFilePdf;
    }

    public function getPathFolderForGenerate(): string {
        return $this->pathFolderForGenerate;
    }

    public function getNameFilePdf(): string {
        return $this->nameFilePdf;
    }

    public function generate(string $view, string $orientation = 'portrait', string $paperSize = 'A4') {

        $orientation === 'portrait' ? $orientation : 'landscape';
        
        if (!\is_dir($this->getPathFolderForGenerate())) {
            if (!\mkdir($this->getPathFolderForGenerate(), 0755)) {
                throw new \ErrorException('Folder ' . $this->getPathFolderForGenerate() . ' not created');
            }
        }

        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->loadHtml($view);
        $dompdf->setPaper($paperSize, $orientation);
        $dompdf->render();
        $output = $dompdf->output();

        $filePdf = $this->getPathFolderForGenerate() . '/' . $this->getNameFilePdf() . '.pdf';

        if (\file_put_contents($filePdf, $output)) {
            return $filePdf;
        } else {
            throw new \ErrorException('Can\'t create ' . $output . ' file. Has permission?');
        }
    }

}
