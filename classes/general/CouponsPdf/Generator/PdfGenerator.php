<?php

namespace GGrach\CouponsPdf\Generator;

use Dompdf\Dompdf;

class PdfGenerator {

    private string $codeCoupon;
    private string $pathFolderForGenerate;

    public function __construct(string $codeCoupon, string $pathFolderForGenerate) {
        $this->codeCoupon = $codeCoupon;
        $this->pathFolderForGenerate = $pathFolderForGenerate;
    }

    public function getCodeCoupon(): string {
        return $this->codeCoupon;
    }

    public function getPathFolderForGenerate(): string {
        return $this->pathFolderForGenerate;
    }

    public function setCodeCoupon(string $codeCoupon): void {
        $this->codeCoupon = $codeCoupon;
    }

    public function setPathFolderForGenerate(string $pathFolderForGenerate): void {
        $this->pathFolderForGenerate = $pathFolderForGenerate;
    }

    public function generate() {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }

}
