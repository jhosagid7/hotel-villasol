@echo off
setlocal enabledelayedexpansion
title Actualizador Seguro - Hosteria Villasol
color 0A

echo ======================================================================
echo           HOSTERIA VILLASOL - ACTUALIZACION SEGURA DESDE GIT
echo ======================================================================
echo.

:: 1. Ir al directorio donde se encuentra este archivo .bat
cd /d %~dp0
echo [1/6] Directorio de trabajo: %CD%
echo.

:: 2. Detectar y agregar Git al PATH si no esta disponible
where git >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [INFO] Git no esta en el PATH global, buscando en ubicaciones estandar...
    if exist C:\Program Files\Git\cmd\git.exe (
        set PATH=%PATH%;C:\Program Files\Git\cmd
    ) else if exist C:\laragon\bin\git\bin\git.exe (
        set PATH=%PATH%;C:\laragon\bin\git\bin
    ) else if exist C:\laragon\bin\git\cmd\git.exe (
        set PATH=%PATH%;C:\laragon\bin\git\cmd
    ) else (
        color 0C
        echo [ERROR] No se encontro Git instalado. Por favor instala Git o abre Laragon.
        echo.
        pause
        exit /b 1
    )
)
echo [2/6] Git detectado correctamente.
echo.

:: 3. Detectar y agregar PHP al PATH si no esta disponible
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [INFO] PHP no esta en el PATH global, buscando en Laragon...
    for /d %%D in (C:\laragon\bin\php\php-*") do (
 if exist %%D\php.exe (
 set PATH=%PATH%;%%D
 goto :php_found
 )
 )
 color 0C
 echo [ERROR] No se encontro PHP. Asegurate de que Laragon este instalado en C:\laragon.
 pause
 exit /b 1
)
:php_found
echo [3/6] PHP detectado correctamente.
echo.

:: 4. Respaldo preventivo de la Base de Datos
echo [4/6] Realizando respaldo preventivo de la base de datos...
if not exist storage\app\backups mkdir storage\app\backups
for /f tokens=2 delims== %%I in ('wmic os get localdatetime /value 2^>nul') do set datetime=%%I
if defined datetime (
 set TIMESTAMP=%datetime:~0,8%_%datetime:~8,6%
) else (
 set TIMESTAMP=%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
 set TIMESTAMP=!TIMESTAMP: =0!
)
set BACKUP_FILE=storage\app\backups\backup_previo_%TIMESTAMP%.sql

where mysqldump >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
 for /d %%D in (C:\laragon\bin\mysql\mysql-*") do (
        if exist %%D\bin\mysqldump.exe (
            set MYSQLDUMP=%%D\bin\mysqldump.exe
            goto :mysqldump_found
        )
    )
    set MYSQLDUMP=mysqldump
) else (
    set MYSQLDUMP=mysqldump
)
:mysqldump_found

%MYSQLDUMP% -u root hosteria-villasol > %BACKUP_FILE% 2>nul
if exist %BACKUP_FILE% (
    echo       Respaldo guardado en: %BACKUP_FILE%
) else (
    echo       [AVISO] No se pudo crear respaldo automatico con mysqldump. Continuando...
)
echo.

:: 5. Descarga de Cambios desde Git
echo [5/6] Conectando con GitHub y descargando mejoras...
if not exist .git (
    echo       Inicializando repositorio Git...
    git init
    git remote add origin https://github.com/jhosagid7/hotel-villasol.git
)

echo       Resguardando configuracion local...
git stash

echo       Descargando rama develop desde GitHub...
git remote set-url origin https://github.com/jhosagid7/hotel-villasol.git
git fetch origin develop
git checkout develop
git pull origin develop

if %ERRORLEVEL% NEQ 0 (
    color 0C
    echo.
    echo [ERROR] Fallo la descarga de Git. Verifica la conexion a Internet.
    echo.
    pause
    exit /b 1
)
echo.

:: 6. Limpieza de cache de Laravel
echo [6/6] Limpiando y renovando cache de Laravel...
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
echo NOTA: Para futuras actualizaciones ya no necesitas este archivo.
echo Solo ve a tu sistema en el navegador: Sistema -^> Actualizar Sistema.
echo.
pause
