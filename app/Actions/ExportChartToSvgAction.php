<?php

declare(strict_types=1);

namespace Modules\Chart\Actions;

use RuntimeException;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

use function Safe\base64_decode;

/**
 * Esporta un chart in formato SVG.
 */
class ExportChartToSvgAction
{
    use QueueableAction;

    /**
     * @return array{path: string, url: string, size: int, filename: string, mime_type: string, exported_at: string|null}
     */
    public function execute(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public'
    ): array {
        $filename = $filename ?? 'chart-'.now()->timestamp.'.svg';

        $svgData = base64_decode($base64Data, true);

        $storedPath = Storage::disk($disk)->put($filename, $svgData);
        if (! is_string($storedPath)) {
            throw new RuntimeException('Failed to save SVG file');
        }

        return [
            'path' => $storedPath,
            'url' => Storage::disk($disk)->url($storedPath),
            'size' => strlen($svgData),
            'filename' => basename($filename),
            'mime_type' => 'image/svg+xml',
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * @param  array<string, mixed>  $chartData
     *
     * @return array{path: string, url: string, size: int, filename: string, mime_type: string, exported_at: string|null}
     */
    public function executeFromChartData(
        array $chartData,
        ?string $filename = null,
        string $disk = 'public'
    ): array {
        return $this->execute(base64_encode($this->chartDataToSvg($chartData)), $filename, $disk);
    }

    /**
     * @param  array<string, mixed>  $chartData
     */
    private function chartDataToSvg(array $chartData): string
    {
        $svg = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">'."\n";
        $svg .= '  <rect width="800" height="600" fill="#ffffff"/>'."\n";

        $title = $this->chartTitle($chartData);
        $svg .= '  <text x="400" y="30" text-anchor="middle" font-size="16" font-weight="bold">'.htmlspecialchars($title).'</text>'."\n";

        $data = $this->chartValues($chartData);
        $labels = $this->chartLabels($chartData);
        $maxValue = $data === [] ? 1.0 : max($data);

        $barWidth = 50;
        $spacing = 20;
        $startX = 100;
        $chartHeight = 400;
        $baseY = 500;
        foreach ($data as $index => $value) {
            $x = $startX + ($index * ($barWidth + $spacing));
            $height = ($value / $maxValue) * $chartHeight;
            $y = $baseY - $height;
            $color = $this->barColor($chartData, $index);

            $svg .= '  <rect x="'.$x.'" y="'.$y.'" width="'.$barWidth.'" height="'.$height.'" fill="'.$color.'"/>'."\n";

            $label = $labels[$index] ?? '';
            $svg .= '  <text x="'.($x + $barWidth / 2).'" y="'.($baseY + 20).'" text-anchor="middle" font-size="10">'.htmlspecialchars($label).'</text>'."\n";
            $svg .= '  <text x="'.($x + $barWidth / 2).'" y="'.($y - 5).'" text-anchor="middle" font-size="10">'.number_format($value, 1).'</text>'."\n";
        }

        $svg .= '</svg>';

        return $svg;
    }

    /**
     * @param  array<string, mixed>  $chartData
     */
    private function chartTitle(array $chartData): string
    {
        $datasets = $chartData['datasets'] ?? null;
        if (! is_array($datasets) || ! isset($datasets[0]) || ! is_array($datasets[0])) {
            return 'Chart';
        }

        $label = $datasets[0]['label'] ?? 'Chart';

        return is_scalar($label) ? (string) $label : 'Chart';
    }

    /**
     * @param  array<string, mixed>  $chartData
     * @return array<int, float>
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
}
