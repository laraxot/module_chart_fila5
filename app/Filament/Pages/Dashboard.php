<?php

declare(strict_types=1);

namespace Modules\Chart\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Chart\Filament\Widgets\Samples as WidgetsSamples;

class Dashboard extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'chart::filament.pages.dashboard';

    /*
    public function mount(): void {
        $user = auth()->user();
        if(!$user->hasRole('super-admin')){
            redirect('/admin');
        }
    }
    */

    public function getHeaderWidgets(): array
    {
        return [
            WidgetsSamples\Bar02Chart::make(),
            // WidgetsSamples\OverlookWidget::make(),
            // WidgetsSamples\OverlookV2Widget::make(),
            WidgetsSamples\Doughnut01Chart::make(),
            WidgetsSamples\Sample01Chart::make(),
        ];
    }
}
