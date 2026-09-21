# Task 002: Implement Advanced Chart Features

## Description
Implement advanced chart features including composite charts, mixed chart types, advanced animations, custom plugins, and advanced data visualization techniques.

## Context
Basic chart widgets are implemented, but users need more advanced features for complex data visualization requirements including composite charts, mixed types, and custom visualizations.

## Requirements

### Functional Requirements
- Composite charts (multiple chart types in one)
- Mixed chart types (line + bar, etc.)
- Advanced animations (custom easing, transitions)
- Custom Chart.js plugins
- Gradient fills and patterns
- Dual Y-axis support
- Stacked charts
- Grouped charts
- Error bars and confidence intervals
- Annotations and markers
- Data zooming and panning
- Chart templates and presets

### Technical Requirements
- Use PHP 8.3 strict typing
- PHPStan Level 10 compliance
- Chart.js 4.x advanced features
- Custom plugin development
- Performance optimization

## Implementation Steps

### 1. Composite Chart System
- [ ] Create `CompositeChartWidget` class
  - Support multiple chart types
  - Separate datasets per type
  - Shared axes configuration
  - Unified legend

- [ ] Create chart type combinations
  - Line + Bar (combo chart)
  - Line + Area
  - Bar + Scatter
  - Pie + Doughnut overlay
  - Radar + Polar

### 2. Mixed Chart Types
- [ ] Implement dataset-level type configuration
  - Each dataset can have different type
  - Automatic axis assignment
  - Legend grouping by type

- [ ] Create `MixedChartWidget` base class
  - Configure multiple chart types
  - Handle type-specific options
  - Manage shared datasets

### 3. Advanced Animations
- [ ] Create `ChartAnimationService`
  - `configureAnimation(array $config): array`
  - `createCustomEasing(string $function): string`
  - `setAnimationDuration(int $ms): void`
  - `setAnimationDelay(int $ms): void`
  - `configureEntryAnimation(string $type): array`

- [ ] Implement animation presets
  - Fade in
  - Slide up
  - Scale in
  - Rotate
  - Custom path animation

### 4. Custom Plugins
- [ ] Create `ChartPluginRegistry`
  - Register custom plugins
  - Load plugins dynamically
  - Plugin configuration

- [ ] Develop custom plugins
  - `DataLabelPlugin` - Custom data labels
  - `TrendLinePlugin` - Add trend lines
  - `ThresholdPlugin` - Highlight thresholds
  - `WatermarkPlugin` - Add watermarks
  - `AnnotationPlugin` - Add annotations
  - `ExportPlugin` - Enhanced export options

### 5. Gradient Fills and Patterns
- [ ] Create `ChartStyleService`
  - `createLinearGradient(array $colors): array`
  - `createRadialGradient(array $colors): array`
  - `createPattern(string $type): array`
  - `applyGradientToDataset(array $dataset, array $gradient): array`

- [ ] Implement gradient presets
  - Sunset gradient
  - Ocean gradient
  - Forest gradient
  - Custom gradient builder

### 6. Dual Y-Axis Support
- [ ] Create `DualAxisChartWidget` class
  - Primary Y-axis (left)
  - Secondary Y-axis (right)
  - Independent scaling
  - Axis synchronization options

- [ ] Implement axis configuration
  - Set axis type (linear, logarithmic, time)
  - Configure axis bounds
  - Add axis labels
  - Style axis lines

### 7. Stacked Charts
- [ ] Implement stacking configuration
  - Vertical stacking
  - Horizontal stacking
  - 100% stacking
  - Grouped stacking

- [ ] Create `StackedChartWidget` classes
  - `StackedBarChartWidget`
  - `StackedLineChartWidget`
  - `StackedAreaChartWidget`

### 8. Error Bars and Confidence Intervals
- [ ] Create `ErrorBarService`
  - `calculateErrorBar(array $data, float $confidence = 0.95): array`
  - `addErrorBarsToDataset(array $dataset, array $errors): array`
  - `calculateStandardDeviation(array $data): float`
  - `calculateConfidenceInterval(array $data, float $confidence): array`

- [ ] Implement error bar rendering
  - Vertical error bars
  - Horizontal error bars
  - Whiskers and caps
  - Custom styling

### 9. Annotations and Markers
- [ ] Create `ChartAnnotationService`
  - `addLineAnnotation(array $config): array`
  - `addBoxAnnotation(array $config): array`
  - `addPointAnnotation(array $config): array`
  - `addLabelAnnotation(array $config): array`

- [ ] Implement annotation types
  - Horizontal/vertical lines
  - Rectangular regions
  - Point markers
  - Text labels
  - Arrows and pointers

### 10. Data Zooming and Panning
- [ ] Create `ChartZoomService`
  - `enableZoom(array $config): array`
  - `enablePan(array $config): array`
  - `setZoomRange(array $range): array`
  - `resetZoom(): void`

- [ ] Implement zoom interactions
  - Mouse wheel zoom
  - Drag to zoom
  - Pan with drag
  - Zoom buttons
  - Reset button

### 11. Chart Templates and Presets
- [ ] Create `ChartTemplateRegistry`
  - Register templates
  - Load templates
  - Save custom templates

- [ ] Implement built-in templates
  - Sales dashboard template
  - Analytics template
  - Financial template
  - Performance template
  - Custom template builder

### 12. Filament Integration
- [ ] Update `ChartWidgetResource`
  - Advanced configuration options
  - Template selector
  - Plugin management
  - Style customization

- [ ] Create `ChartBuilder` page
  - Visual chart builder
  - Drag-and-drop components
  - Live preview
  - Export configuration

### 13. Actions
- [ ] Create `ApplyChartTemplateAction`
- [ ] Create `SaveChartTemplateAction`
- [ ] Create `AddChartAnnotationAction`
- [ ] Create `ConfigureChartZoomAction`

### 14. Tests
- [ ] Create `CompositeChartTest`
  - Test composite rendering
  - Test mixed chart types
  - Test dual axis

- [ ] Create `AdvancedAnimationTest`
  - Test custom animations
  - Test animation timing
  - Test animation performance

- [ ] Create `ChartPluginTest`
  - Test plugin loading
  - Test plugin functionality
  - Test plugin interactions

- [ ] Create `AdvancedStyleTest`
  - Test gradient fills
  - Test patterns
  - Test error bars

### 15. Documentation
- [ ] Create advanced features guide
- [ ] Document composite charts
- [ ] Create plugin development guide
- [ ] Document animation presets
- [ ] Add style reference

## Acceptance Criteria
- [ ] Composite charts render correctly
- [ ] Mixed chart types work seamlessly
- [ ] Advanced animations perform well
- [ ] Custom plugins are functional
- [ ] Gradients and patterns render
- [ ] Dual Y-axis scales correctly
- [ ] Stacked charts stack properly
- [ ] Error bars display accurately
- [ ] Annotations are visible
- [ ] Zoom and pan work smoothly
- [ ] Templates can be saved/loaded
- [ ] All tests pass with 85%+ coverage
- [ ] PHPStan Level 10 compliant

## Dependencies
- Task 001: Chart Widget System
- Xot module (base classes)
- Filament 5.x (admin UI)
- Chart.js 4.x (charting library)

## Estimated Time
- Composite charts: 6 hours
- Mixed chart types: 4 hours
- Advanced animations: 5 hours
- Custom plugins: 8 hours (5 plugins × 1.6h)
- Gradients/patterns: 4 hours
- Dual Y-axis: 3 hours
- Stacked charts: 3 hours
- Error bars: 4 hours
- Annotations: 4 hours
- Zoom/pan: 4 hours
- Templates: 4 hours
- Filament integration: 4 hours
- Actions: 2 hours
- Tests: 8 hours
- Documentation: 3 hours

**Total: 66 hours (8-9 days)**

## Priority
**Medium** - Enhances chart capabilities

## Related Tasks
- Task 001: Chart Widget System
- Task 003: Chart Analytics and Insights
- Task 004: JpGraph Integration for PDF

## Notes
- Keep performance in mind with complex charts
- Use debouncing for zoom events
- Implement lazy loading for large datasets
- Cache plugin configurations
- Provide clear documentation for custom plugins
- Test on various devices and screen sizes

---

**Created**: 2026-01-31
**Status**: Pending
**Assignee**: TBD