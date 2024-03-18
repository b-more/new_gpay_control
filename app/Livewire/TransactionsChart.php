<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Transfer;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Payments';

    protected static ?string $maxHeight = '300px';

    protected static string $color = 'success';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;



        $data = Trend::model(Payment::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('received_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Total Payments',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            //'labels' => $data->map(fn (TrendValue $value) => $value->date),
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
