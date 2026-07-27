@echo off
setlocal
set "ROOT=%~dp0"
set "PHP_BIN=%ROOT%runtime\php83\php.exe"
if not exist "%PHP_BIN%" (
  echo [ERROR] Bundled PHP not found: %PHP_BIN%
  exit /b 1
)
"%PHP_BIN%" %*
