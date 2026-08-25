<?php

declare(strict_types=1);

namespace Modules\Chart\Actions\ChartJs;

use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

final class ExportToSvgAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $chartData
     * @param  array<string, mixed>  $options
     * @return array{
     *     svg_content: string,
     *     export_options: array{width: int, height: int, filename: string, title: string, includeStyles: bool},
     *     timestamp: int
     * }
     */
    public function execute(array $chartData, array $options = []): array
    {
        $exportOptions = $this->resolveExportOptions($chartData, $options);
        $payload = $this->extractChartPayload($chartData);

        return [
            'svg_content' => $this->generateSvgFromData($payload, $exportOptions),
            'export_options' => $exportOptions,
            'timestamp' => \time(),
        ];
    }

    /**
     * @param  array<string, mixed>  $chartData
     * @param  array<string, mixed>  $options
     * @return array{width: int, height: int, filename: string, title: string, includeStyles: bool}
     */
    private function resolveExportOptions(array $chartData, array $options): array
    {
        /** @var float|int|string|null $widthInput */
        $widthInput = $options['width'] ?? $chartData['width'] ?? 800;
        $width = $this->sanitizeDimension($widthInput);

        /** @var float|int|string|null $heightInput */
        $heightInput = $options['height'] ?? $chartData['height'] ?? 600;
        $height = $this->sanitizeDimension($heightInput);

       $filename = SafeStringCastAction::cast($options['filename'] ?? ('chart_'.\time().'.svg'));
        $title = SafeStringCastAction::cast($options['title'] ?? $chartData['title'] ?? 'Chart');
        $includeStyles = isset($options['includeStyles']) ? (bool) $options['includeStyles'] : true;

        return [
            'width' => $width,
            'height' => $height,
            'filename' => $filename !== '' ? $filename : 'chart.svg',
            'title' => $title,
            'includeStyles' => $includeStyles,
        ];
    }

   /**
     * @param  array<string, mixed>  $chartData
     * @return array{
     *     type: string,
     *     datasets: list<array{label: string|null, data: list<float>, backgroundColor: list<string>, borderColor: list<string>}>,
     *     labels: list<string>
     * }
     */
    private function extractChartPayload(array $chartData): array
    {
        $type = \is_string($chartData['type'] ?? null) ? (string) $chartData['type'] : 'bar';
        $data = $chartData['data'] ?? [];
        if (! \is_array($data)) {
            $data = [];
        }

        $rawDatasets = $data['datasets'] ?? [];
        $rawLabels = $data['labels'] ?? [];

        $datasets = [];
        if (\is_array($rawDatasets)) {
            foreach ($rawDatasets as $dataset) {
                if (! \is_array($dataset)) {
                    continue;
                }

                $numericData = $this->normalizeNumericSeries($dataset['data'] ?? []);
                if ($numericData === []) {
                    continue;
                }

                $datasets[] = [
                   'label' => isset($dataset['label']) ? SafeStringCastAction::cast($dataset['label']) : null,
                    'data' => $numericData,
                    'backgroundColor' => $this->normalizeColorPalette($dataset['backgroundColor'] ?? null, \count($numericData)),
                    'borderColor' => $this->normalizeColorPalette($dataset['borderColor'] ?? null, \count($numericData)),
                ];
            }
        }

       $labels = $this->normalizeLabels(is_array($rawLabels) ? $rawLabels : [], $datasets);

        return [
            'type' => $type,
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    /**
     * @return list<float>
     */
    private function normalizeNumericSeries(mixed $rawValues): array
    {
        if (! \is_array($rawValues)) {
            return [];
        }

        $series = [];
        foreach ($rawValues as $value) {
            if (is_numeric($value)) {
                $series[] = (float) $value;
            }
        }

        return $series;
    }

    /**
     * @return list<string>
     */
    private function normalizeColorPalette(mixed $rawColors, int $length): array
    {
        if (\is_string($rawColors)) {
            $rawColors = [$rawColors];
        }

        if (! \is_array($rawColors)) {
            $rawColors = [];
        }

        $palette = [];
        foreach ($rawColors as $color) {
            if (\is_string($color) && \trim($color) !== '') {
                $palette[] = $color;
            }
        }

        if ($palette === []) {
            return \array_fill(0, \max($length, 1), '#36A2EB');
        }

        if (\count($palette) < $length) {
            $palette = \array_merge(
                $palette,
                \array_fill(\count($palette), $length - \count($palette), $palette[0])
            );
        }

        return \array_slice($palette, 0, $length);
    }

    /**
     * @param  array<int|string, mixed>  $rawLabels
    * @param  list<array{label: string|null, data: list<float>, backgroundColor: list<string>, borderColor: list<string>}>  $datasets
     * @return list<string>
     */
    private function normalizeLabels(array $rawLabels, array $datasets): array
    {
        $labels = [];
        foreach ($rawLabels as $label) {
            if (\is_string($label)) {
                $labels[] = $label;
            } elseif (is_numeric($label)) {
                $labels[] = (string) $label;
            }
        }

        $maxDataPoints = $this->maxDataPoints($datasets);
        if ($labels === [] && $maxDataPoints > 0) {
            for ($index = 0; $index < $maxDataPoints; $index++) {
                $labels[] = \sprintf('Label %d', $index + 1);
            }
        }

        if (\count($labels) < $maxDataPoints) {
            for ($index = \count($labels); $index < $maxDataPoints; $index++) {
                $labels[] = \sprintf('Label %d', $index + 1);
            }
        }

        return $labels;
    }

    /**
    * @param  list<array{label: string|null, data: list<float>, backgroundColor: list<string>, borderColor: list<string>}>  $datasets
     */
    private function maxDataPoints(array $datasets): int
    {
        $max = 0;
        foreach ($datasets as $dataset) {
            $max = \max($max, \count($dataset['data']));
        }

        return $max;
    }

   /**
     * @param  array{
     *     type: string,
     *     datasets: list<array{label: string|null, data: list<float>, backgroundColor: list<string>, borderColor: list<string>}>,
     *     labels: list<string>
     * }  $chartPayload
     * @param  array{width: int, height: int, filename: string, title: string, includeStyles: bool}  $options
     */
    private function generateSvgFromData(array $chartPayload, array $options): string
    {
        $width = $options['width'];
        $height = $options['height'];

        $svgParts = [];
        $svgParts[] = \sprintf('<svg width="%d" height="%d" xmlns="http://www.w3.org/2000/svg">', $width, $height);

        if ($options['title'] !== '') {
            $svgParts[] = \sprintf('<title>%s</title>', $this->escape($options['title']));
        }

        if ($options['includeStyles']) {
            $svgParts[] = '<style>';
            $svgParts[] = '.chart-title{font:bold 16px sans-serif;fill:#333;}';
            $svgParts[] = '.chart-axis{stroke:#333;stroke-width:1;}';
            $svgParts[] = '.chart-grid{stroke:#ccc;stroke-dasharray:2,2;}';
            $svgParts[] = '</style>';
        }

        $svgParts[] = match ($chartPayload['type']) {
            'bar' => $this->generateBarChartSvg($chartPayload['datasets'], $chartPayload['labels'], $width, $height),
            'line' => $this->generateLineChartSvg($chartPayload['datasets'], $chartPayload['labels'], $width, $height),
            'doughnut', 'pie' => $this->generatePieChartSvg($chartPayload['datasets'], $chartPayload['labels'], $width, $height),
            default => $this->generateGenericChartSvg($width, $height),
        };

        $svgParts[] = '</svg>';

        return implode('', $svgParts);
    }

   /**
     * @param  list<array<string, mixed>>  $datasets
     * @param  list<string>  $labels
     */
    private function generateBarChartSvg(array $datasets, array $labels, int $width, int $height): string
    {
        return '';
    }

   /**
     * @param  list<array<string, mixed>>  $datasets
     * @param  list<string>  $labels
     */
    private function generateLineChartSvg(array $datasets, array $labels, int $width, int $height): string
    {
        return '';
    }

   /**
     * @param  list<array<string, mixed>>  $datasets
     * @param  list<string>  $labels
     */
    private function generatePieChartSvg(array $datasets, array $labels, int $width, int $height): string
    {
        return '';
    }

    private function generateGenericChartSvg(int $width, int $height): string
    {
        return \sprintf(
            '<text x="%d" y="%d" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#666">%s</text>',
            (int) ($width / 2),
            (int) ($height / 2),
            $this->escape('Chart Export')
        );
    }

    private function sanitizeDimension(int|float|string|null $value): int
    {
        if (\is_string($value)) {
            $value = (int) $value;
        }

        $dimension = (int) $value;

        return \max($dimension, 1);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }
}
