<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

final class PricingService
{
    public function calculate(array $product, array $group, int $quantity, bool $isDelayed = false): array
    {
        $step = max(1, (int) ($product['step_num'] ?? 1));
        $units = (int) ceil($quantity / $step);
        $unitCost = $isDelayed && !empty($product['price_cost_delayed']) ? (int) $product['price_cost_delayed'] : (int) $product['price_cost'];
        $baseCost = $unitCost * $units;

        $markupMode = (string) ($group['markup_mode'] ?? 'fixed');
        $markupValue = (float) ($group['markup_value'] ?? 0);
        $sell = $baseCost;
        if ($markupMode === 'percent') {
            if ($markupValue > 1 && $markupValue <= 100) {
                $markupValue /= 100;
            }
            $sell = (int) round($baseCost * (1 + $markupValue));
        } else {
            $sell = $baseCost + (int) round($markupValue * $units);
        }

        $discountRate = $this->discountRate((int) ($product['id'] ?? 0), $quantity);
        $sell = (int) round($sell * $discountRate);
        return [
            'base_cost' => $baseCost,
            'sell_price' => max(0, $sell),
            'units' => $units,
            'discount_rate' => $discountRate,
            'profit' => max(0, $sell - $baseCost),
        ];
    }

    public function discountRate(int $productId, int $quantity): float
    {
        if ($productId <= 0) {
            return 1.0;
        }
        $pdo = \XiaoMiSlop\Core\Database::connection();
        $stmt = $pdo->prepare('SELECT discount_rate FROM product_discounts WHERE product_id = ? AND active = 1 AND min_quantity <= ? ORDER BY min_quantity DESC LIMIT 1');
        $stmt->execute([$productId, $quantity]);
        $rate = $stmt->fetchColumn();
        if ($rate === false) {
            return 1.0;
        }
        return (float) clamp((float) $rate, 0.01, 1.0);
    }
}
