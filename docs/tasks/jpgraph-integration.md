# Task 003: Integrate JpGraph for PDF Chart Generation

## Description
Integrate JpGraph 4.4.2 library for server-side chart generation, specifically for embedding charts in PDF documents with high-quality output suitable for reports and print.

## Context
Chart.js is excellent for interactive web-based charts, but for PDF reports and print materials, server-side chart generation with JpGraph provides better quality and control over the output.

## Requirements

### Functional Requirements
- JpGraph 4.4.2 integration
- Server-side chart generation
- High-quality PNG/SVG output
- PDF embedding support
- Chart-to-image conversion
- Batch chart generation
- Caching for performance
- Multi-language support
- Italian formatting (currency, dates)

### Technical Requirements
- Use PHP 8.3 strict typing
- PHPStan Level 10 compliance
- JpGraph 4.4.2 (latest PHP 8.2 compatible)
- Custom JpGraph services
- Exception handling
- Memory management

## Implementation Steps

### 1. JpGraph Installation
- [ ] Install JpGraph via Composer
  - `composer require amenadiel/jpgraph`
  - Verify PHP 8.2 compatibility
  - Configure autoloading

- [ ] Create JpGraph configuration
  - Font paths
  - Cache directory
  - Image quality settings
  - Default chart dimensions

### 2. Base JpGraph Service
- [ ] Create `JpGraphBaseService` abstract class
  - Common JpGraph setup
  - Error handling
  - Memory management
  - Strict typing

- [ ] Create `JpGraphException` class
  - Custom exception for JpGraph errors
  - Detailed error messages
  - Logging integration

### 3. Chart Type Services
- [ ] Create `JpGraphLineChartService`
  - `createLineChart(array $data, array $labels): string` (returns image path)
  - `createMultiLineChart(array $datasets, array $labels): string`
  - Configure line styles
  - Add markers
  - Add grid lines

- [ ] Create `JpGraphBarChartService`
  - `createBarChart(array $data, array $labels): string`
  - `createGroupedBarChart(array $datasets, array $labels): string`
  - `createStackedBarChart(array $datasets, array $labels): string`
  - Configure bar colors
  - Add value labels

- [ ] Create `JpGraphPieChartService`
  - `createPieChart(array $data, array $labels): string`
  - `createDoughnutChart(array $data, array $labels): string`
  - `create3DPieChart(array $data, array $labels): string`
  - Add legends
  - Add percentage labels

- [ ] Create `JpGraphScatterChartService`
  - `createScatterChart(array $xData, array $yData): string`
  - Add trend lines
  - Configure markers

### 4. Italian Localization Service
- [ ] Create `JpGraphItalianFormatter`
  - `formatCurrency(float $amount): string`
  - `formatNumber(float $number, int $decimals = 2): string`
  - `formatDate(Carbon $date): string`
  - `getItalianMonthLabels(): array`
  - `getItalianDayLabels(): array`

- [ ] Configure Italian fonts
  - Set font family
  - Set encoding (UTF-8)
  - Configure font sizes

### 5. Caching System
- [ ] Create `JpGraphCacheService`
  - `generateCacheKey(array $data, string $chartType): string`
  - `getCachedChart(string $cacheKey): string|null`
  - `cacheChart(string $cacheKey, string $imagePath, int $ttl = 3600): void`
  - `clearCache(): void`
  - `clearExpiredCache(): int`

- [ ] Implement cache storage
  - File-based caching
  - Redis caching option
  - Cache key generation
  - Cache invalidation

### 6. Image Processing
- [ ] Create `JpGraphImageService`
  - `generateImage(Graph $graph): string` (save to temp)
  - `convertToPng(string $path): string`
  - `convertToSvg(string $path): string`
  - `optimizeImage(string $path): bool`
  - `getMimeType(string $path): string`

- [ ] Implement image optimization
  - Compression settings
  - Resolution adjustment
  - Color depth optimization

### 7. PDF Integration
- [ ] Create `JpGraphPdfService`
  - `embedChartInPdf(string $chartPath, string $pdfPath): string`
  - `createChartPdf(array $charts, string $outputPath): string`
  - `addChartToExistingPdf(string $chartPath, string $pdfPath, int $x, int $y): string`

- [ ] Integrate with PDF libraries
  - HTML2PDF support
  - Spatie PDF support
  - TCPDF support

### 8. Batch Generation
- [ ] Create `JpGraphBatchService`
  - `generateBatch(array $chartConfigs): array`
  - `generateReportCharts(array $data): array`
  - `queueBatchGeneration(array $chartConfigs): string` (job ID)

- [ ] Create batch generation job
  - `GenerateJpGraphBatchJob`
  - Process multiple charts
  - Error handling
  - Progress tracking

### 9. Memory Management
- [ ] Implement memory-efficient generation
  - Process charts in chunks
  - Free memory after each chart
  - Monitor memory usage
  - Handle large datasets

### 10. Filament Integration
- [ ] Create `JpChartWidget` for Filament
  - Display JpGraph charts
  - Download button
  - Configuration options

- [ ] Update chart resources
  - Add JpGraph export option
  - Add PDF generation option
  - Show generated images

### 11. API Endpoints
- [ ] `POST /api/charts/jpgraph/generate` - Generate chart
- [ ] `GET /api/charts/jpgraph/{id}` - Get generated chart
- [ ] `POST /api/charts/jpgraph/batch` - Batch generation
- [ ] `GET /api/charts/jpgraph/pdf/{id}` - Get PDF with charts

### 12. Actions
- [ ] Create `GenerateJpGraphChartAction`
- [ ] Create `GenerateChartPdfAction`
- [ ] Create `ClearJpGraphCacheAction`

### 13. Tests
- [ ] Create `JpGraphLineChartTest`
  - Test line chart generation
  - Test multi-line chart
  - Test styling options

- [ ] Create `JpGraphBarChartTest`
  - Test bar chart generation
  - Test grouped bars
  - Test stacked bars

- [ ] Create `JpGraphPieChartTest`
  - Test pie chart generation
  - Test doughnut chart
  - Test 3D pie chart

- [ ] Create `JpGraphCacheTest`
  - Test caching
  - Test cache invalidation
  - Test cache expiration

- [ ] Create `JpGraphPdfTest`
  - Test PDF embedding
  - Test batch PDF generation

### 14. Documentation
- [ ] Create JpGraph integration guide
- [ ] Document chart type services
- [ ] Create PDF generation tutorial
- [ ] Add Italian formatting guide
- [ ] Create troubleshooting guide

## Acceptance Criteria
- [ ] JpGraph generates high-quality charts
- [ ] All chart types work correctly
- [ ] Italian formatting is applied
- [ ] Caching improves performance
- [ ] PDF embedding works
- [ ] Batch generation processes efficiently
- [ ] Memory usage is optimized
- [ ] All tests pass with 85%+ coverage
- [ ] PHPStan Level 10 compliant

## Dependencies
- Xot module (base classes)
- Filament 5.x (admin UI)
- JpGraph 4.4.2 (charting library)
- PDF libraries (HTML2PDF, Spatie)

## Estimated Time
- JpGraph installation: 1 hour
- Base service: 3 hours
- Chart type services: 12 hours (4 services × 3h)
- Italian localization: 3 hours
- Caching system: 4 hours
- Image processing: 3 hours
- PDF integration: 4 hours
- Batch generation: 3 hours
- Memory management: 2 hours
- Filament integration: 3 hours
- API endpoints: 2 hours
- Actions: 2 hours
- Tests: 8 hours
- Documentation: 3 hours

**Total: 53 hours (7 days)**

## Priority
**High** - Critical for PDF reports

## Related Tasks
- Task 001: Chart Widget System
- Task 002: Advanced Chart Features
- Quaeris module (PDF reports)

## Notes
- JpGraph 4.4.2 is the latest version supporting PHP 8.2
- Always use custom exception handling
- Implement proper cleanup of temporary files
- Use caching to improve performance
- Test with large datasets for memory issues
- Support Italian localization (currency: €, dates: DD/MM/YYYY)
- Consider using queue for batch generation

---

**Created**: 2026-01-31
**Status**: Pending
**Assignee**: TBD