<?php

declare(strict_types=1);

namespace Modules\Chart\Actions;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Spatie\QueueableAction\QueueableAction;

/**
 * Esporta un chart in formato PNG
 * 
 * Pattern ottimizzato da Fila4 per Fila5
 */
class ExportChartToPngAction
{
    use QueueableAction;

    /**
     * Esporta un chart in PNG
     *
     * @param string      $base64Data Dati base64 del chart
     * @param string|null $filename   Nome del file (opzionale)
     * @param string      $disk       Disco storage Laravel
     * @param int         $quality    Qualità PNG (1-100)
     *
     * @return array{path: string, url: string, size: int, filename: string}
     */
    public function execute(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public',
        int $quality = 95
    ): array {
        // Genera filename unico se non fornito
        $filename = $filename ?? 'chart-'.now()->timestamp.'.png';
        
        // Decodifica base64
        $imageData = base64_decode($base64Data, true);
        
        if ($imageData === false) {
            throw new \RuntimeException('Invalid base64 data');
        }
        
        // Usa Intervention Image per processare l'immagine
        $manager = new ImageManager([
            'driver' => 'gd',
        ]);
        
        $image = $manager->make($imageData);
        
        // Salva il file
        $path = Storage::disk($disk)->put($filename, $image->encode('png', $quality)->getEncoded());
        
        if ($path === false) {
            throw new \RuntimeException('Failed to save PNG file');
        }
        
        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'size' => filesize(Storage::disk($disk)->path($path)),
            'filename' => basename($filename),
            'mime_type' => 'image/png',
            'quality' => $quality,
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * Esporta PNG da dati Chart.js
     *
     * @param array       $chartData Dati del chart Chart.js
     * @param string|null $filename  Nome del file
     * @param string      $disk      Disco storage
     * @param int         $quality   Qualità PNG
     *
     * @return array{path: string, url: string, size: int, filename: string}
     */
    public function executeFromChartData(
        array $chartData,
        ?string $filename = null,
        string $disk = 'public',
        int $quality = 95
    ): array {
        // Converti Chart.js data in immagine (via canvas o libreria)
        $imageData = $this->chartDataToImage($chartData);
        
        $base64Data = base64_encode($imageData);
        
        return $this->execute($base64Data, $filename, $disk, $quality);
    }

    /**
     * Converte Chart.js data in immagine
     * 
     * Implementazione base - può essere estesa con librerie come chartjs-node-canvas
     */
    private function chartDataToImage(array $chartData): string
    {
        // Crea immagine con GD
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Background bianco
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        // Titolo
        $title = $chartData['datasets'][0]['label'] ?? 'Chart';
        $black = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 5, $width / 2 - 50, 10, $title, $black);
        
        // Simple bar chart
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
            $height = (int) (($value / $maxValue) * $chartHeight);
            $y = $baseY - $height;
            
            // Colore barra
            $colorHex = $chartData['datasets'][0]['backgroundColor'][$index] ?? '#4CAF50';
            $color = $this->hexToRgbColor($image, $colorHex);
            
            imagefilledrectangle($image, $x, $y, $x + $barWidth, $baseY, $color);
            
            // Label
            $label = $labels[$index] ?? '';
            imagestring($image, 1, $x + 15, $baseY + 5, $label, $black);
            
            // Value
            imagestring($image, 1, $x + 15, $y - 10, number_format($value, 1), $black);
        }
        
        // Output come stringa
        ob_start();
        imagepng($image, null, 9);
        $imageData = ob_get_clean();
        
        imagedestroy($image);
        
        return $imageData;
    }

    /**
     * Converte hex color a RGB per GD
     */
    private function hexToRgbColor($image, string $hex): int
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) === 3) {
            $r = hexdec($hex[0].$hex[0]);
            $g = hexdec($hex[1].$hex[1]);
            $b = hexdec($hex[2].$hex[2]);
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return imagecolorallocate($image, $r, $g, $b);
    }
}
