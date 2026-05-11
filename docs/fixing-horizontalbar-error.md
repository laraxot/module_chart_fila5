# Fixing Chart.js "horizontalBar" Error

## Problem
The application was throwing an `Uncaught Error: "horizontalBar" is not a registered controller` when rendering certain charts. This was caused by the use of the `horizontalBar` chart type, which was deprecated in Chart.js v3 and removed in later versions.

## Diagnosis
- The error originated because the database stored `horizontalBar` as the chart type in the `charts` JSON field of the `question_charts` table.
- The `AnswersChartData` DTO simply passed this value through to the frontend via `getChartJsType()`.
- The frontend `QuestionChartItemWidget` used this type directly, causing Chart.js to fail.

## Solution
We modified `Modules/Chart/app/Datas/AnswersChartData.php` to intercept the `horizontalBar` type and convert it to the modern Chart.js equivalent.

### Changes 
1.  **Updated `getChartJsType()`**: Added a case for `horizontalBar` to return `'bar'`.
    ```php
    case 'horizontalBar':
        $type = 'bar';
        break;
    ```

2.  **Updated `getChartJsOptionsArray()`**: Added a check to set `indexAxis: 'y'` when the original type was `horizontalBar`, effectively rotating the bar chart to be horizontal.
    ```php
    if ($this->chart->type === 'horizbar1' || $this->chart->type === 'horizontalBar') {
        $options['indexAxis'] = 'y';
    }
    ```

3.  **Updated Helper Methods**: Updated `determineValueSuffix`, `determineIndexAxis`, `buildBarLabelsJs`, and `buildBarTitleJs` to recognize `horizontalBar` and treat it identically to `horizbar1` (the existing internal type for horizontal bars).

## Verification
- The chart now renders as a standard bar chart but with the Y-axis as the index axis, replicating the "horizontal bar" appearance using supported Chart.js syntax.
- The error `Uncaught Error: "horizontalBar" is not a registered controller` is resolved.

## Future Considerations
- Ideally, a migration should be run to update the database records from `horizontalBar` to `bar` (with an additional flag or metadata for orientation), but this code fix ensures backward compatibility with existing data without requiring immediate database migration.
