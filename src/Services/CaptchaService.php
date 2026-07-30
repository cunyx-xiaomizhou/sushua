<?php
declare(strict_types=1);

namespace XiaoMiSlop\Services;

use XiaoMiSlop\Core\Session;

final class CaptchaService
{
    public function issue(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        Session::put('captcha_code', strtolower($code));
        return $code;
    }

    public function verify(string $value): bool
    {
        $expected = strtolower((string) Session::get('captcha_code', ''));
        Session::forget('captcha_code');
        return $expected !== '' && hash_equals($expected, strtolower(trim($value)));
    }

    public function svg(): string
    {
        $code = $this->issue();
        $theme = (new SettingsService())->getJson('theme_config', []);
        $background = $this->themeColor($theme, 'captcha_bg', '#eef2ff');
        $lineColor = $this->themeColor($theme, 'captcha_line', '#8aa4d6');
        $textColor = $this->themeColor($theme, 'captcha_text', '#1f3f78');
        $lines = '';
        for ($i = 0; $i < 5; $i++) {
            $lines .= sprintf('<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="%s" stroke-width="1"/>', random_int(0, 140), random_int(0, 48), random_int(0, 140), random_int(0, 48), $lineColor);
        }
        $text = '';
        foreach (str_split($code) as $i => $char) {
            $text .= sprintf('<text x="%d" y="34" font-size="26" font-family="Arial" font-weight="700" fill="%s" transform="rotate(%d %d 28)">%s</text>', 12 + $i * 30, $textColor, random_int(-8, 8), 25 + $i * 30, htmlspecialchars($char, ENT_QUOTES, 'UTF-8'));
        }
        return '<svg xmlns="http://www.w3.org/2000/svg" width="140" height="48" viewBox="0 0 140 48"><rect width="140" height="48" rx="10" fill="'.$background.'"/>'.$lines.$text.'</svg>';
    }

    private function themeColor(array $theme, string $key, string $fallback): string
    {
        $value = trim((string) ($theme[$key] ?? ''));
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? strtolower($value) : $fallback;
    }
}
