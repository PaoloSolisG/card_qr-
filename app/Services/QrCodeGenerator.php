<?php

namespace App\Services;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeGenerator
{
    /**
     * Render the QR as SVG markup (sharp at any size, for screen and print).
     * Pass $standalone = false to embed it inline in HTML without the XML header.
     */
    public function svg(string $data, bool $standalone = true): string
    {
        return $this->render($data, [
            'outputInterface' => QRMarkupSVG::class,
            'svgAddXmlHeader' => $standalone,
        ]);
    }

    /**
     * Render the QR as a high-resolution PNG binary.
     */
    public function png(string $data, int $scale = 20): string
    {
        return $this->render($data, [
            'outputInterface' => QRGdImagePNG::class,
            'scale' => $scale,
        ]);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function render(string $data, array $options): string
    {
        $options = new QROptions([
            'eccLevel' => EccLevel::M,
            'outputBase64' => false,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
            ...$options,
        ]);

        return (new QRCode($options))->render($data);
    }
}
