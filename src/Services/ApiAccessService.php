<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

final class ApiAccessService
{
    public function status(array $user): array
    {
        $settings = new SettingsService();
        $groupService = new UserGroupService();
        $group = null;
        foreach ($groupService->all() as $candidate) {
            if ((int) $candidate['id'] === (int) $user['user_group_id']) {
                $group = $candidate;
                break;
            }
        }

        $conditionMode = (string) $settings->get('api_condition_mode', 'total_consume');
        $operator = (string) $settings->get('api_condition_operator', '>=');
        $expected = (int) $settings->get('api_condition_value', '0');
        $current = match ($conditionMode) {
            'total_recharge' => (int) $user['total_recharge'],
            'balance' => (int) $user['balance'],
            'invite_count' => (int) $user['invite_count'],
            default => (int) $user['total_consume'],
        };
        $conditionPassed = match ($operator) {
            '>' => $current > $expected,
            '<' => $current < $expected,
            '<=' => $current <= $expected,
            '=' => $current === $expected,
            default => $current >= $expected,
        };

        $groupAllow = (int) ($group['allow_api_default'] ?? 0) === 1;
        $override = $user['api_enabled_override'];
        $overrideAllow = $override === null ? null : ((int) $override === 1);
        $isAgent = (int) ($user['strategy_agent'] ?? 0) === 1;
        $allowed = $isAgent && $conditionPassed && ($overrideAllow ?? $groupAllow);
        if ($user['status'] !== 'active') {
            $allowed = false;
        }

        return [
            'allow' => $allowed,
            'condition_mode' => $conditionMode,
            'condition_operator' => $operator,
            'condition_value' => $expected,
            'condition_current' => $current,
            'group_default' => $groupAllow,
            'override' => $override,
            'is_agent' => $isAgent,
        ];
    }
}
