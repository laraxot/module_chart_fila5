# Task 001: Implement Complete Chart Widget System

## Description
Create a comprehensive chart widget system with support for multiple chart types, data sources, customization options, and seamless integration with Filament dashboards.

## Context
The Chart module needs a flexible and powerful widget system to display various types of data visualizations. The current implementation is limited and needs significant enhancement.

## Requirements

### Functional Requirements
- Support for multiple chart types (line, bar, pie, doughnut, radar, polar, scatter, bubble)
- Flexible data source configuration (models, queries, APIs, CSV)
- Chart customization (colors, labels, legends, tooltips)
- Real-time data updates
- Interactive charts (zoom, pan, drill-down)
- Export charts as images/PDF
- Responsive design
- Accessibility support

### Technical Requirements
- Use PHP 8.3 strict typing
- PHPStan Level 10 compliance
- Chart.js 4.x for rendering
- Extend XotBaseChartWidget
- RawJs for complex options
- Efficient data aggregation

## Implementation Steps

### 1. Base Widget Classes
- [ ] Create `XotBaseChartWidget` abstract class (if not exists)
  - Base configuration methods
  - Common data loading logic
  - Default styling
  - Caching support
  - Strict typing

- [ ] Create chart type base classes
  - `XotLineChartWidget`
  - `XotBarChartWidget`
  - `XotPieChartWidget`
  - `XotDoughnutChartWidget`
  - `XotRadarChartWidget`
  - `XotPolarChartWidget`
  - `XotScatterChartWidget`
  - `XotBubbleChartWidget`

### 2. Data Source System
- [ ] Create `ChartDataSource` interface
  - `getData(): array`
  - `getLabels(): array`
  - `getDatasets(): array`

- [ ] Create data source implementations
  - `ModelDataSource` - Query Eloquent models
  - `QueryBuilderDataSource` - Custom queries
  - `ApiDataSource` - External API calls
  - `CsvDataSource` - CSV file import
  - `ArrayDataSource` - Direct array data

- [ ] Create `ChartDataSourceFactory`
  - `create(string $type, array $config): ChartDataSource`

### 3. Chart Configuration System
- [ ] Create `ChartConfiguration` class
  - Chart type
  - Colors palette
  - Labels configuration
  - Legend settings
  - Tooltip settings
  - Axis settings
  - Animation options
  - Responsive options

- [ ] Create color palettes
  - Default palette
  - Pastel palette
  - Dark theme palette
  - Custom palette support

### 4. Widget Templates
- [ ] Create standard widget templates
  - `SimpleLineChartWidget` - Basic line chart
  - `SimpleBarChartWidget` - Basic bar chart
  - `SimplePieChartWidget` - Basic pie chart
  - `MultiSeriesChartWidget` - Multiple datasets
  - `TimeSeriesChartWidget` - Time-based data
  - `ComparisonChartWidget` - Compare two datasets

### 5. Data Aggregation Service
- [ ] Create `ChartDataAggregationService`
  - `aggregateByDay(array $data): array`
  - `aggregateByWeek(array $data): array`
  - `aggregateByMonth(array $data): array`
  - `aggregateByYear(array $data): array`
  - `calculateAverage(array $data): float`
  - `calculateSum(array $data): float`
  - `calculateGrowthRate(array $data): float`
  - `smoothData(array $data, string $method = 'moving_average'): array`

### 6. Real-time Updates
- [ ] Create `RealTimeChartWidget` trait
  - WebSocket connection
  - Auto-refresh interval
  - Live data updates
  - Animation on update

- [ ] Create `ChartDataBroadcaster`
  - Broadcast chart updates
  - Subscribe to data changes
  - Update connected clients

### 7. Export Functionality
- [ ] Create `ChartExporter` service
  - `exportAsImage(ChartWidget $widget): string` (PNG)
  - `exportAsPdf(ChartWidget $widget): string` (PDF)
  - `exportAsSvg(ChartWidget $widget): string` (SVG)
  - `exportAsCsv(ChartWidget $widget): string` (CSV)

- [ ] Add export buttons to widgets
  - Download as image
  - Download as PDF
  - Copy chart link

### 8. Interactive Features
- [ ] Implement zoom functionality
- [ ] Implement pan functionality
- [ ] Implement drill-down on click
- [ ] Implement tooltips on hover
- [ ] Implement legend filtering

### 9. Accessibility
- [ ] Add ARIA labels to charts
- [ ] Provide keyboard navigation
- [ ] Add screen reader descriptions
- [ ] Ensure color contrast compliance
- [ ] Provide data table alternative

### 10. Filament Integration
- [ ] Create `ChartWidgetResource`
  - Widget management
  - Create/Edit widgets
  - Widget preview
  - Widget templates

- [ ] Create `ChartDashboardPage`
  - Widget grid layout
  - Drag-and-drop widget arrangement
  - Add/remove widgets
  - Configure widget settings

### 11. Actions
- [ ] Create `CreateChartWidgetAction`
- [ ] Create `UpdateChartWidgetAction`
- [ ] Create `DeleteChartWidgetAction`
- [ ] Create `ExportChartAction`
- [ ] Create `RefreshChartAction`

### 12. Tests
- [ ] Create `ChartWidgetTest`
  - Test widget rendering
  - Test data loading
  - Test chart configuration

- [ ] Create `ChartDataSourceTest`
  - Test model data source
  - Test query builder source
  - Test API data source

- [ ] Create `ChartDataAggregationTest`
  - Test aggregation methods
  - Test smoothing algorithms

- [ ] Create `ChartExportTest`
  - Test image export
  - Test PDF export
  - Test CSV export

### 13. Documentation
- [ ] Create widget development guide
- [ ] Document data source configuration
- [ ] Create chart type reference
- [ ] Add accessibility guidelines
- [ ] Create troubleshooting guide

## Acceptance Criteria
- [ ] All chart types render correctly
- [ ] Data sources work reliably
- [ ] Charts are customizable
- [ ] Real-time updates work
- [ ] Export functionality works
- [ ] Widgets are accessible
- [ ] All tests pass with 85%+ coverage
- [ ] PHPStan Level 10 compliant

## Dependencies
- Xot module (base classes)
- Filament 5.x (dashboard integration)
- Chart.js 4.x (charting library)
- Laravel Broadcasting (real-time updates)

## Estimated Time
- Base widget classes: 8 hours
- Data source system: 6 hours
- Configuration system: 4 hours
- Widget templates: 8 hours (8 templates × 1h)
- Data aggregation: 4 hours
- Real-time updates: 4 hours
- Export functionality: 4 hours
- Interactive features: 3 hours
- Accessibility: 3 hours
- Filament integration: 5 hours
- Actions: 2 hours
- Tests: 8 hours
- Documentation: 3 hours

**Total: 62 hours (8 days)**

## Priority
**High** - Core functionality for chart module

## Related Tasks
- Task 002: Advanced Chart Features
- Task 003: Chart Analytics and Insights
- Task 004: JpGraph Integration for PDF

## Notes
- Always extend XotBaseChartWidget, never Filament\Widgets\ChartWidget directly
- Use RawJs only for formatter functions in getOptions()
- Return array from getOptions(), not RawJs object
- Implement caching for heavy data queries
- Use responsive design for mobile compatibility
- Follow WCAG 2.1 AA accessibility standards

---

**Created**: 2026-01-31
**Status**: Pending
**Assignee**: TBD