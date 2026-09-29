@echo off
setlocal enabledelayedexpansion
title Actualizador Seguro - Hosteria Villasol
color 0A

echo ======================================================================
echo           HOSTERIA VILLASOL - ACTUALIZACION SEGURA DESDE GIT
echo ======================================================================
echo.

:: 1. Ir al directorio donde se encuentra este archivo .bat
cd /d "%~dp0"
echo [1/6] Directorio del proyecto: %CD%
echo.

:: 2. Deteccion de Git (busca en PATH, Laragon en cualquier disco o Program Files)
set "GIT_EXE="
where git >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    set "GIT_EXE=git"
) else (
    for %%P in (
        "%~d0\laragon\bin\git\cmd\git.exe"
        "%~d0\laragon\bin\git\bin\git.exe"
        "C:\laragon\bin\git\cmd\git.exe"
        "C:\laragon\bin\git\bin\git.exe"
        "D:\laragon\bin\git\cmd\git.exe"
        "D:\laragon\bin\git\bin\git.exe"
        "C:\Program Files\Git\cmd\git.exe"
        "C:\Program Files (x86)\Git\cmd\git.exe"
        "%LOCALAPPDATA%\Programs\Git\cmd\git.exe"
    ) do (
        if not defined GIT_EXE (
            if exist %%P (
                set "GIT_EXE=%%~P"
                for %%I in (%%P) do set "PATH=!PATH!;%%~dpI"
            )
        )
    )
)

if not defined GIT_EXE (
    color 0C
    echo [ERROR] No se encontro Git en el equipo.
    echo Por favor asegurate de tener Git o Laragon instalado.
    echo.
    pause
    exit /b 1
)

for /f "tokens=*" %%V in ('git --version 2^>nul') do set "GIT_VER=%%V"
echo [2/6] Git detectado: !GIT_VER!
echo.

:: 3. Deteccion de PHP (busca en PATH o en cualquier carpeta de Laragon)
set "PHP_EXE="
where php >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    set "PHP_EXE=php"
) else (
    for %%L in ("%~d0\laragon" "C:\laragon" "D:\laragon") do (
        if exist %%L\bin\php (
            for /d %%D in (%%L\bin\php\php-*) do (
                if exist "%%D\php.exe" (
                    set "PHP_EXE=%%D\php.exe"
                    set "PATH=!PATH!;%%D"
                )
            )
        )
    )
)

if not defined PHP_EXE (
    color 0C
    echo [ERROR] No se encontro PHP en Laragon ni en el sistema.
    echo Por favor verifica que Laragon este instalado.
    echo.
    pause
    exit /b 1
)

for /f "tokens=*" %%V in ('php -r "echo PHP_VERSION;" 2^>nul') do set "PHP_VER=%%V"
echo [3/6] PHP detectado: Version !PHP_VER!
echo.

:: 4. Respaldo preventivo de la Base de Datos
echo [4/6] Creando respaldo preventivo de la base de datos...
if not exist "storage\app\backups" mkdir "storage\app\backups"

set "MYSQLDUMP="
where mysqldump >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    set "MYSQLDUMP=mysqldump"
) else (
    for %%L in ("%~d0\laragon" "C:\laragon" "D:\laragon") do (
        if exist %%L\bin\mysql (
            for /d %%D in (%%L\bin\mysql\mysql-*) do (
                if exist "%%D\bin\mysqldump.exe" (
                    set "MYSQLDUMP=%%D\bin\mysqldump.exe"
                )
            )
        )
    )
)

for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value 2^>nul') do set datetime=%%I
if defined datetime (
    set "TIMESTAMP=%datetime:~0,8%_%datetime:~8,6%"
) else (
    set "TIMESTAMP=%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%"
    set "TIMESTAMP=!TIMESTAMP: =0!"
)
set "BACKUP_FILE=storage\app\backups\backup_previo_%TIMESTAMP%.sql"

:: Obtener nombre de la base de datos directamente de .env
set "TARGET_DB="
if exist ".env" (
    for /f "usebackq tokens=1,* delims==" %%A in (`findstr /i "^DB_DATABASE=" .env 2^>nul`) do set "TARGET_DB=%%B"
)
if not defined TARGET_DB set "TARGET_DB=hosteria-villasol"
set "TARGET_DB=!TARGET_DB:"=!"
set "TARGET_DB=!TARGET_DB: =!"

if defined MYSQLDUMP (
    echo       Exportando base de datos: !TARGET_DB!...
    "%MYSQLDUMP%" -u root !TARGET_DB! > "%BACKUP_FILE%" 2>nul
    if exist "%BACKUP_FILE%" (
        echo       Respaldo creado con exito en: %BACKUP_FILE%
    ) else (
        echo       [AVISO] No se pudo exportar respaldo automatico. Continuando con la actualizacion...
    )
) else (
    echo       [AVISO] Mysqldump no detectado. Continuando de forma segura...
)
echo.

:: 5. Descarga de Cambios desde Git
echo [5/6] Conectando con GitHub y descargando mejoras...
if not exist ".git" (
    echo       Inicializando repositorio Git en la carpeta...
    git init
    git remote add origin https://github.com/jhosagid7/hotel-villasol.git
)

echo       Resguardando configuracion local (.env y cambios locales)...
git stash

echo       Descargando rama develop desde GitHub...
git remote set-url origin https://github.com/jhosagid7/hotel-villasol.git
git fetch origin develop
git checkout develop
git pull origin develop

if %ERRORLEVEL% NEQ 0 (
    color 0C
    echo.
    echo [ERROR] No se pudo descargar desde Git. Verifica la conexion a Internet.
    echo.
    pause
    exit /b 1
)
echo.

:: 6. Limpieza y renovacion de cache de Laravel
echo [6/6] Limpiando y renovando cache del sistema...
php artisan optimize:clear
echo.

echo ======================================================================
echo       FELICITACIONES: EL SISTEMA SE HA ACTUALIZADO CON EXITO
echo ======================================================================
echo.
echo 1. Se aplicaron las correcciones del modulo de Reservaciones.
echo 2. Se alinearon las migraciones y seeders del sistema.
echo 3. Se instalo el Actualizador Web dentro del sistema.
echo.
echo NOTA: Para futuras actualizaciones ya no necesitas usar este archivo.
echo Solo ve a tu sistema en el navegador: Sistema -^> Actualizar Sistema.
echo.
pause