<?php

declare(strict_types=1);

namespace Modules\Chart\Actions;

use RuntimeException;
use GdImage;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

use function Safe\base64_decode;
use function Safe\filesize;
use function Safe\imagecreatetruecolor;
use function Safe\imagefill;
use function Safe\imagefilledrectangle;
use function Safe\imagepng;
use function Safe\imagestring;
use function Safe\ob_get_clean;
use function Safe\ob_start;

/**
 * Esporta un chart in formato PNG.
 */
class ExportChartToPngAction
{
    use QueueableAction;

    /**
     * @return array{path: string, url: string, size: int, filename: string, mime_type: string, quality: int, exported_at: string|null}
     */
    public function execute(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public',
        int $quality = 95
    ): array {
        $filename = $filename ?? 'chart-'.now()->timestamp.'.png';

        $imageData = base64_decode($base64Data, true);

        $storedPath = Storage::disk($disk)->put($filename, $imageData);
        if (! is_string($storedPath)) {
            throw new RuntimeException('Failed to save PNG file');
        }

        return [
            'path' => $storedPath,
            'url' => Storage::disk($disk)->url($storedPath),
            'size' => filesize(Storage::disk($disk)->path($storedPath)),
            'filename' => basename($filename),
            'mime_type' => 'image/png',
            'quality' => $quality,
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * @param  array<string, mixed>  $chartData
     *
     * @return array{path: string, url: string, size: int, filename: string, mime_type: string, quality: int, exported_at: string|null}
     */
    public function executeFromChartData(
        array $chartData,
        ?string $filename = null,
        string $disk = 'public',
        int $quality = 95
    ): array {
        $imageData = $this->chartDataToImage($chartData);

        return $this->execute(base64_encode($imageData), $filename, $disk, $quality);
    }

    /**
     * @param  array<string, mixed>  $chartData
     */
    private function chartDataToImage(array $chartData): string
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        $white = $this->allocateColor($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);

        $title = $this->chartTitle($chartData);
        $black = $this->allocateColor($image, 0, 0, 0);
        imagestring($image, 5, (int) ($width / 2 - 50), 10, $title, $black);

        $data = $this->chartValues($chartData);
        $labels = $this->chartLabels($chartData);
        $maxValue = $data === [] ? 1.0 : max($data);

        $barWidth = 50;
        $spacing = 20;
        $startX = 100;
        $chartHeight = 400;
        $baseY = 500;

        foreach ($data as $index => $value) {
            $numericValue = is_numeric($value) ? (float) $value : 0.0;
            $x = $startX + ($index * ($barWidth + $spacing));
            $barHeight = (int) (($numericValue / $maxValue) * $chartHeight);
            $y = $baseY - $barHeight;

            $colorHex = $this->barColor($chartData, $index);
            $color = $this->hexToRgbColor($image, $colorHex);

            imagefilledrectangle($image, $x, $y, $x + $barWidth, $baseY, $color);

            $label = $labels[$index] ?? '';
            imagestring($image, 1, $x + 15, $baseY + 5, is_scalar($label) ? (string) $label : '', $black);
            imagestring($image, 1, $x + 15, $y - 10, number_format($numericValue, 1), $black);
        }

        ob_start();
        imagepng($image, null, 9);
        $imageData = ob_get_clean();

        return $imageData;
    }

    /**
     * @param  array<string, mixed>  $chartData
     */
    private function chartTitle(array $chartData): string
    {
        $datasets = $chartData['datasets'] ?? null;
        if (! is_array($datasets)) {
            return 'Chart';
        }

        $first = $datasets[0] ?? null;
        if (! is_array($first)) {
            return 'Chart';
        }

        $label = $first['label'] ?? 'Chart';

        return is_scalar($label) ? (string) $label : 'Chart';
    }

    /**
     * @param  array<string, mixed>  $chartData
     * @return array<int, float|int>
     */
    private function chartValues(array $chartData): array
    {
        $datasets = $chartData['datasets'] ?? null;
        if (! is_array($datasets) || ! isset($datasets[0]) || ! is_array($datasets[0])) {
            return [];
        }

        $data = $datasets[0]['data'] ?? [];
        if (! is_array($data)) {
            return [];
        }

        $values = [];
        foreach ($data as $value) {
            $values[] = is_numeric($value) ? (float) $value : 0.0;
        }

        return $values;
    }

    /**
     * @param  array<string, mixed>  $chartData
     * @return array<int, string>
     */
    private function chartLabels(array $chartData): array
    {
        $labels = $chartData['labels'] ?? [];
        if (! is_array($labels)) {
            return [];
        }

        $normalized = [];
        foreach ($labels as $label) {
            $normalized[] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $chartData
     */
    private function barColor(array $chartData, int $index): string
    {
        $datasets = $chartData['datasets'] ?? null;
        if (! is_array($datasets) || ! isset($datasets[0]) || ! is_array($datasets[0])) {
            return '#4CAF50';
        }

        $colors = $datasets[0]['backgroundColor'] ?? null;
        if (! is_array($colors) || ! isset($colors[$index])) {
            return '#4CAF50';
        }

        $color = $colors[$index];

        return is_scalar($color) ? (string) $color : '#4CAF50';
    }

    private function hexToRgbColor(GdImage $image, string $hex): int
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $r = $this->clampRgbChannel((int) hexdec($hex[0].$hex[0]));
            $g = $this->clampRgbChannel((int) hexdec($hex[1].$hex[1]));
            $b = $this->clampRgbChannel((int) hexdec($hex[2].$hex[2]));
        } else {
            $r = $this->clampRgbChannel((int) hexdec(substr($hex, 0, 2)));
            $g = $this->clampRgbChannel((int) hexdec(substr($hex, 2, 2)));
            $b = $this->clampRgbChannel((int) hexdec(substr($hex, 4, 2)));
        }

        return $this->allocateColor($image, $r, $g, $b);
    }

    /** @return int<0, 255> */
    private function clampRgbChannel(int $channel): int
    {
        return max(0, min(255, $channel));
    }

    /**
     * @param  int<0, 255>  $red
     * @param  int<0, 255>  $green
     * @param  int<0, 255>  $blue
     */
    private function allocateColor(GdImage $image, int $red, int $green, int $blue): int
    {
        $color = imagecolorallocate($image, $red, $green, $blue);
        if ($color === false) {
            throw new RuntimeException('Failed to allocate color');
        }

        return $color;
    }
}
