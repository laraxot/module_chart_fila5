<?php

declare(strict_types=1);

namespace Modules\Chart\Actions;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Spatie\QueueableAction\QueueableAction;

/**
 * Esporta un chart in formato SVG
 * 
 * Pattern ottimizzato da Fila4 per Fila5
 */
class ExportChartToSvgAction
{
    use QueueableAction;

    /**
     * Esporta un chart in SVG
     *
     * @param string      $base64Data Dati base64 del chart
     * @param string|null $filename   Nome del file (opzionale)
     * @param string      $disk       Disco storage Laravel
     *
     * @return array{path: string, url: string, size: int, filename: string}
     */
    public function execute(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public'
    ): array {
        // Genera filename unico se non fornito
        $filename = $filename ?? 'chart-'.now()->timestamp.'.svg';
        
        // Decodifica base64
        $svgData = base64_decode($base64Data, true);
        
        if ($svgData === false) {
            throw new \RuntimeException('Invalid base64 data');
        }
        
        // Salva il file
        $path = Storage::disk($disk)->put($filename, $svgData);
        
        if ($path === false) {
            throw new \RuntimeException('Failed to save SVG file');
        }
        
        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'size' => strlen($svgData),
            'filename' => basename($filename),
            'mime_type' => 'image/svg+xml',
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * Esporta SVG da dati Chart.js
     *
     * @param array       $chartData Dati del chart Chart.js
     * @param string|null $filename  Nome del file
     * @param string      $disk      Disco storage
     *
     * @return array{path: string, url: string, size: int, filename: string}
     */
    public function executeFromChartData(
        array $chartData,
        ?string $filename = null,
        string $disk = 'public'
    ): array {
        // Converti Chart.js data in SVG
        $svg = $this->chartDataToSvg($chartData);
        
        $base64Data = base64_encode($svg);
        
        return $this->execute($base64Data, $filename, $disk);
    }

    /**
     * Converte Chart.js data in SVG
     * 
     * Implementazione base - può essere estesa con librerie come chartjs-node-canvas
     */
    private function chartDataToSvg(array $chartData): string
    {
        // SVG header
        $svg = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600">'."\n";
        
        // Background
        $svg .= '  <rect width="800" height="600" fill="#ffffff"/>'."\n";
        
        // Title
        $title = $chartData['datasets'][0]['label'] ?? 'Chart';
        $svg .= '  <text x="400" y="30" text-anchor="middle" font-size="16" font-weight="bold">'.htmlspecialchars($title).'</text>'."\n";
        
        // Simple bar chart representation
        $data = $chartData['datasets'][0]['data'] ?? [];
        $labels = $chartData['labels'] ?? [];
        $maxValue = max($data) ?: 1;
        
        $barWidth = 50;
        $spacing = 20;
        $startX = 100;
        $chartHeight = 400;
        $baseY = 500;
        
        foreach ($data as $index => $value) {
            $x = $startX + ($index * ($barWidth + $spacing));
            $height = ($value / $maxValue) * $chartHeight;
            $y = $baseY - $height;
            
            $color = $chartData['datasets'][0]['backgroundColor'][$index] ?? '#4CAF50';
            
            $svg .= '  <rect x="'.$x.'" y="'.$y.'" width="'.$barWidth.'" height="'.$height.'" fill="'.$color.'"/>'."\n";
            
            // Label
            $label = $labels[$index] ?? '';
            $svg .= '  <text x="'.($x + $barWidth / 2).'" y="'.($baseY + 20).'" text-anchor="middle" font-size="10">'.htmlspecialchars($label).'</text>'."\n";
            
            // Value
            $svg .= '  <text x="'.($x + $barWidth / 2).'" y="'.($y - 5).'" text-anchor="middle" font-size="10">'.number_format($value, 1).'</text>'."\n";
        }
        
        $svg .= '</svg>';
        
        return $svg;
    }
}
