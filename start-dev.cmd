@echo off
setlocal
for %%I in ("%~dp0.") do set "ROOT=%%~fI"
set "ROUTER=%ROOT%\router.php"
where php >nul 2>nul
if errorlevel 1 (
  echo [ERROR] PHP was not found in PATH. Please install PHP 8.3 or later and enable curl, mbstring, mysqli, openssl and pdo_mysql.
  pause
  exit /b 1
)
echo [INFO] XiaoMiSlop dev server is starting...
echo [INFO] Listen: 0.0.0.0:3400
echo [INFO] Local:  http://127.0.0.1:3400/
echo [INFO] LAN:    http://SERVER-IP:3400/
echo [INFO] This project is PHP + local Vue assets. No separate compile step is required.
php -S 0.0.0.0:3400 -t "%ROOT%" "%ROUTER%"
set "EXITCODE=%ERRORLEVEL%"
if not "%EXITCODE%"=="0" (
  echo.
  echo [ERROR] Dev server exited with code %EXITCODE%
  pause
)
exit /b %EXITCODE%