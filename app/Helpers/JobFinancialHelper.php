<?php

namespace App\Helpers;

use App\Models\Job;
use LogicException;

class JobFinancialHelper
{
    public static function totals(Job $job): array
    {
        self::assertRelationsLoaded($job);

        $totalCosts = $job->costLines->sum('amount');
        $totalRevenue = $job->revenueLines->sum('amount');
        $totalAdjustments = $job->adjustmentLines->sum('amount');

        return [
            'total_costs' => $totalCosts,
            'total_revenue' => $totalRevenue,
            'total_adjustments' => $totalAdjustments,
            'gross_profit' => $totalRevenue - $totalCosts + $totalAdjustments,
        ];
    }

    protected static function assertRelationsLoaded(Job $job): void
    {
        foreach (['costLines', 'revenueLines', 'adjustmentLines'] as $relation) {
            if (! $job->relationLoaded($relation)) {
                throw new LogicException("Relation [$relation] must be loaded before computing totals.");
            }
        }
    }
}
