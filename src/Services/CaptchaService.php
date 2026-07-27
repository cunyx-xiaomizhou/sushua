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
        $lines = '';
        for ($i = 0; $i < 5; $i++) {
            $lines .= sprintf('<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="#%s" stroke-width="1"/>', random_int(0, 140), random_int(0, 48), random_int(0, 140), random_int(0, 48), str_random_hex());
        }
        $text = '';
        foreach (str_split($code) as $i => $char) {
            $text .= sprintf('<text x="%d" y="34" font-size="26" font-family="Arial" font-weight="700" fill="#%s" transform="rotate(%d %d 28)">%s</text>', 12 + $i * 30, str_random_hex(), random_int(-8, 8), 25 + $i * 30, htmlspecialchars($char, ENT_QUOTES, 'UTF-8'));
        }
        return '<svg xmlns="http://www.w3.org/2000/svg" width="140" height="48" viewBox="0 0 140 48"><rect width="140" height="48" rx="10" fill="#eef2ff"/>'.$lines.$text.'</svg>';
    }
}

function str_random_hex(): string
{
    return substr(strtoupper(bin2hex(random_bytes(3))), 0, 6);
}
