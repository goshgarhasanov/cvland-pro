<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Billing\Enums\OrderStatus;
use App\Domain\Billing\Models\Order;
use App\Domain\Catalog\Models\Template;
use App\Domain\CV\Enums\CvStatus;
use App\Domain\CV\Models\Cv;
use App\Domain\Identity\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ümumi göstəricilər';

    protected ?string $description = 'CVLAND PRO platformasının cari vəziyyəti';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfPrevMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfPrevMonth = $now->copy()->subMonth()->endOfMonth();

        // Users
        $totalUsers = User::count();
        $usersThisMonth = User::where('created_at', '>=', $startOfMonth)->count();

        // CVs
        $totalCvs = Cv::count();
        $publishedCvs = Cv::where('status', CvStatus::Published->value)->count();

        // Templates
        $activeTemplates = Template::where('is_active', true)->count();

        // Revenue this month (paid orders)
        $revenueThisMonthMinor = (int) Order::where('status', OrderStatus::Paid->value)
            ->where('paid_at', '>=', $startOfMonth)
            ->sum('amount_minor');

        $revenuePrevMonthMinor = (int) Order::where('status', OrderStatus::Paid->value)
            ->whereBetween('paid_at', [$startOfPrevMonth, $endOfPrevMonth])
            ->sum('amount_minor');

        $revenueThisMonth = number_format($revenueThisMonthMinor / 100, 2, '.', ' ');

        $revenueDescription = 'Bu ay ödənilmiş sifarişlər';
        $revenueIcon = 'heroicon-m-arrow-trending-up';
        $revenueColor = 'success';

        if ($revenuePrevMonthMinor > 0) {
            $deltaPct = (($revenueThisMonthMinor - $revenuePrevMonthMinor) / $revenuePrevMonthMinor) * 100;
            $revenueDescription = ($deltaPct >= 0 ? '+' : '') . number_format($deltaPct, 1) . '% keçən aya nisbətən';
            if ($deltaPct < 0) {
                $revenueIcon = 'heroicon-m-arrow-trending-down';
                $revenueColor = 'danger';
            }
        }

        // Pending orders
        $pendingOrders = Order::where('status', OrderStatus::Pending->value)->count();

        return [
            Stat::make('İstifadəçilər', (string) $totalUsers)
                ->description($usersThisMonth . ' bu ay qoşulub')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->chart($this->buildChart(User::class, 'created_at')),

            Stat::make('CV-lər', (string) $totalCvs)
                ->description($publishedCvs . ' dərc olunub')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('info')
                ->chart($this->buildChart(Cv::class, 'created_at')),

            Stat::make('Bu ayın gəliri', $revenueThisMonth . ' AZN')
                ->description($revenueDescription)
                ->descriptionIcon($revenueIcon)
                ->color($revenueColor),

            Stat::make('Aktiv şablonlar', (string) $activeTemplates)
                ->description('Kataloqda mövcuddur')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('success'),

            Stat::make('Gözləmədə sifarişlər', (string) $pendingOrders)
                ->description('Ödəniş tələb edən sifarişlər')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),
        ];
    }

    /**
     * Build a 7-day count chart for the given model.
     *
     * @return array<int>
     */
    private function buildChart(string $modelClass, string $column): array
    {
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $counts[] = (int) $modelClass::whereDate($column, $day->toDateString())->count();
        }

        return $counts;
    }
}
