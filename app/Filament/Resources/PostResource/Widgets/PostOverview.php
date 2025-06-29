<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostOverview extends BaseWidget
{
    protected ?string $heading = 'Analytics';

    protected ?string $description = 'Jumlah posting blog yang diterbitkan per bulan.';

    protected function getStats(): array
    {
        $publishTrend = Trend::query(
            Post::query()->where('status', 'PUBLISH')
        )
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $draftTrend = Trend::query(
            Post::query()->where('status', 'DRAF')
        )
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Post Publish', Post::whereStatus('PUBLISH')->count())
                ->description('Jumlah artikel yang dipublikasikan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($publishTrend->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),

            Stat::make('Post Draf', Post::whereStatus('DRAF')->count())
                ->description('Jumlah artikel yang belum dipublikasikan')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->chart($draftTrend->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('warning'),

        ];
    }

    protected function getColumns(): int
    {
        return 2; // Full width (bisa juga 2, 3 jika ingin kolom berdampingan)
    }
}
