$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$php = Join-Path $root 'runtime\php83\php.exe'
if (-not (Test-Path $php)) {
    throw "Bundled PHP not found: $php"
}
& $php -S 127.0.0.1:3400 -t $root (Join-Path $root 'router.php')
