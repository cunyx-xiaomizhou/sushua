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

        $conditionMode = (string) $settings->get('api_condition_mode', 'total_recharge');
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
        $override = $user['api_enabled_override'] ?? null;
        $policy = $this->connectPolicy($user);

        $policyAllow = match ($policy) {
            'agent' => true,
            'user' => false,
            default => $groupAllow,
        };
        if ($override !== null && $override !== '') {
            $policyAllow = (int) $override === 1;
        }

        $accountActive = (($user['status'] ?? 'active') === 'active');
        $canGenerateKey = $conditionPassed && $accountActive;
        $allowed = $policyAllow && $accountActive;

        return [
            'allow' => $allowed,
            'can_generate_key' => $canGenerateKey,
            'condition_mode' => $conditionMode,
            'condition_operator' => $operator,
            'condition_value' => $expected,
            'condition_current' => $current,
            'condition_passed' => $conditionPassed,
            'group_default' => $groupAllow,
            'override' => $override,
            'connect_policy' => $policy,
            'is_agent' => $policy === 'agent',
        ];
    }

    private function connectPolicy(array $user): string
    {
        if ((int) ($user['strategy_agent'] ?? 0) === 1) {
            return 'agent';
        }
        if ((int) ($user['strategy_user'] ?? 0) === 1) {
            return 'user';
        }
        return 'default';
    }
}
