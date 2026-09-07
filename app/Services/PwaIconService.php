<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Generates the FANORA icon set at build time using PHP GD.
 *
 * The mark is an abstract symbol combining:
 *  - a central node (the fan / creator)
 *  - an orbit ring (the audience)
 *  - two satellite dots (content & connection)
 * It never depicts explicit content.
 */
class PwaIconService
{
    protected const MAGENTA = [233, 30, 99];
    protected const PURPLE = [124, 58, 237];
    protected const DARK = [8, 8, 8];

    /** @return array<string, int> list of generated files */
    public function generateAll(string $outputDir): array
    {
        abort_if(! function_exists('imagecreatetruecolor'), 'Extensão GD não disponível.');

        $files = [];

        foreach ([1024, 512, 192, 64, 32] as $size) {
            $path = "{$outputDir}/icon-{$size}.png";
            $this->renderBackground($size, $path);
            $files[$path] = $size;
        }

        // Maskable variant (transparent background) for PWA.
        $this->renderMaskable("{$outputDir}/icon-192-maskable.png", 192);
        $files["{$outputDir}/icon-192-maskable.png"] = 192;

        Log::info('PWA icons generated', ['files' => array_keys($files)]);

        return $files;
    }

    protected function renderBackground(int $size, string $path): void
    {
        $img = imagecreatetruecolor($size, $size);

        // Diagonal brand gradient.
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $t = ($x + $y) / (2 * $size);
                $r = (int) (self::MAGENTA[0] + (self::PURPLE[0] - self::MAGENTA[0]) * $t);
                $g = (int) (self::MAGENTA[1] + (self::PURPLE[1] - self::MAGENTA[1]) * $t);
                $b = (int) (self::MAGENTA[2] + (self::PURPLE[2] - self::MAGENTA[2]) * $t);

                imagesetpixel($img, $x, $y, imagecolorallocate($img, $r, $g, $b));
            }
        }

        $this->drawSymbol($img, $size);

        imagepng($img, $path);
        imagedestroy($img);
    }

    protected function renderMaskable(string $path, int $size): void
    {
        $img = imagecreatetruecolor($size, $size);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        $this->drawSymbol($img, $size, filled: false);

        imagepng($img, $path);
        imagedestroy($img);
    }

    protected function drawSymbol($img, int $size, bool $filled = true): void
    {
        $center = (int) ($size / 2);
        $unit = $size / 100;

        // Orbit ring (thin, white).
        $ringColor = imagecolorallocate($img, 255, 255, 255);
        imagesetthickness($img, max(2, (int) $unit));
        imageellipse($img, $center, $center, (int) (70 * $unit), (int) (70 * $unit), $ringColor);

        // Central node (fan / creator).
        $nodeR = (int) (8 * $unit);
        imagefilledellipse($img, $center, $center, $nodeR * 2, $nodeR * 2, $ringColor);

        // Content satellite.
        $satR = (int) (4 * $unit);
        $contentAngle = 45.0;
        $x1 = $center + (int) (32 * $unit * cos(deg2rad($contentAngle)));
        $y1 = $center + (int) (32 * $unit * sin(deg2rad($contentAngle)));
        imagefilledellipse($img, $x1, $y1, $satR * 2, $satR * 2, $ringColor);

        // Connection satellite.
        $x2 = $center + (int) (32 * $unit * cos(deg2rad($contentAngle + 120)));
        $y2 = $center + (int) (32 * $unit * sin(deg2rad($contentAngle + 120)));
        imagefilledellipse($img, $x2, $y2, $satR * 2, $satR * 2, $ringColor);

        // Soft dark core behind the node for depth.
        if ($filled) {
            $coreColor = imagecolorallocate($img, 8, 8, 8);
            imagefilledellipse($img, $center, $center, (int) (28 * $unit), (int) (28 * $unit), $coreColor);
            imagefilledellipse($img, $center, $center, $nodeR * 2, $nodeR * 2, $ringColor);
        }
    }
}