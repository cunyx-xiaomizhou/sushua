$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$php = Get-Command php -ErrorAction SilentlyContinue
if (-not $php) {
    throw 'PHP was not found in PATH. Please install PHP 8.3 or later and enable curl, mbstring, mysqli, openssl and pdo_mysql.'
}
Write-Host '[INFO] XiaoMiSlop dev server is starting...'
Write-Host '[INFO] Listen: 0.0.0.0:3400'
Write-Host '[INFO] Local:  http://127.0.0.1:3400/'
Write-Host '[INFO] LAN:    http://你的服务器IP:3400/'
Write-Host '[INFO] This project is PHP + local Vue assets. No separate compile step is required.'
& $php.Source -S 0.0.0.0:3400 -t $root (Join-Path $root 'router.php')