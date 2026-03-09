<?php

declare(strict_types=1);

namespace Modules\Chart\Actions;

use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert as WebmozartAssert;

use function Safe\base64_decode;
use function Safe\json_encode;
use function Safe\preg_replace;
use function Safe\unpack;

class ExportChartToPngAction
{
    use QueueableAction;

    /**
     * @return array{path: string, url: string, size: int, filename: string, quality: int, format: string}
     */
    public function execute(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public',
        int $quality = 95,
    ): array {
        $cleanedData = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
        WebmozartAssert::string($cleanedData, 'Failed to clean base64 data');
        $imageData = base64_decode($cleanedData);

        $filename = $filename ?? 'chart-'.uniqid().'.png';

        $result = Storage::disk($disk)->put($filename, $imageData);
        if ($result === false) {
            throw new RuntimeException('Failed to save PNG file');
        }

        return [
            'path' => $filename,
            'url' => Storage::disk($disk)->url($filename),
            'size' => Storage::disk($disk)->size($filename),
            'filename' => $filename,
            'quality' => $quality,
            'format' => 'png',
        ];
    }

    public function executeForPdf(
        string $base64Data,
        ?string $filename = null,
        string $disk = 'public',
    ): array {
        return $this->execute($base64Data, $filename, $disk, 100);
    }

    public function validatePngFile(string $filename, string $disk = 'public'): bool
    {
        if (! Storage::disk($disk)->exists($filename)) {
            return false;
        }

        $content = (string) Storage::disk($disk)->get($filename);
        $pngSignature = "\x89PNG\r\n\x1a\n";

        return substr($content, 0, 8) === $pngSignature;
    }
}
