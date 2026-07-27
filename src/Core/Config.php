<?php
declare(strict_types=1);

namespace XiaoMiSlop\Core;

final class Config
{
    private static ?array $items = null;

    public static function load(): array
    {
        if (self::$items !== null) return self::$items;
        $file = base_path('storage/config.php');
        $defaults = ['installed'=>false,'app'=>['name'=>'XiaoMiSlop','timezone'=>'Asia/Shanghai','debug'=>true],'database'=>['host'=>'127.0.0.1','port'=>3306,'database'=>'','username'=>'','password'=>'','charset'=>'utf8mb4']];
        $config = $defaults;
        if (file_exists($file)) {
            $loaded = require $file;
            if (is_array($loaded)) $config = array_replace_recursive($defaults, $loaded);
        }
        date_default_timezone_set((string) ($config['app']['timezone'] ?? 'Asia/Shanghai'));
        self::$items = $config;
        return self::$items;
    }

    public static function reset(): void { self::$items = null; self::load(); }
    public static function get(string $key, mixed $default = null): mixed { return array_get(self::load(), $key, $default); }
    public static function isInstalled(): bool { return (bool) self::get('installed', false) && file_exists(base_path('storage/install.lock')); }
}
