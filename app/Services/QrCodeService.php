<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate and persist a QR PNG for the given payload. Returns the stored path.
     */
    public function generateAndStore(string $payload): string
    {
        // Use SVG output to avoid the Imagick extension dependency required for PNG.
        $svg = QrCode::format('svg')
            ->size(320)
            ->errorCorrection('M')
            ->generate($payload);

        $relativePath = "qr_codes/{$payload}.svg";
        Storage::disk('public')->put($relativePath, $svg);

        return $relativePath;
    }
}
