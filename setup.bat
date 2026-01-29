@echo off
echo ===============================
echo Media Library Setup Script
echo ===============================

:: -------------------------------
:: Pfade setzen
:: -------------------------------
set XAMPP_PATH=C:\xampp
set Htdocs_PATH=%XAMPP_PATH%\htdocs
set SQL_PATH=%~dp0Setup\
set DummyData_PATH=%SQL_PATH%Setup\

:: -------------------------------
:: Media Library-Ordner erstellen
:: -------------------------------
mkdir "%Htdocs_PATH%\gruppe1_phpd"
set Project_PATH=%Htdocs_PATH%\gruppe1_phpd

:: -------------------------------
:: Prüfen, ob XAMPP existiert
:: -------------------------------
if not exist "%XAMPP_PATH%" (
    echo XAMPP-Pfad nicht gefunden: %XAMPP_PATH%
    pause
    exit /b
)

:: -------------------------------
:: Backend kopieren
:: -------------------------------
echo Kopiere Backend nach %Project_PATH%\Backend...
C:\Windows\System32\xcopy.exe /E /I /Y "%~dp0Backend" "%Project_PATH%\Backend"

:: -------------------------------
:: Frontend kopieren
:: -------------------------------
echo Kopiere Frontend nach %Project_PATH%\Frontend...
C:\Windows\System32\xcopy.exe /E /I /Y "%~dp0public" "%Project_PATH%\public"

:: -------------------------------
:: Documentation kopieren
:: -------------------------------
echo Kopiere Frontend nach %Project_PATH%\Documentation...
C:\Windows\System32\xcopy.exe /E /I /Y "%~dp0Documentation" "%Project_PATH%\Documentation"

:: -------------------------------
:: Prüfen, ob mysql.exe existiert
:: -------------------------------
if not exist "%XAMPP_PATH%\mysql\bin\mysql.exe" (
    echo mysql.exe nicht gefunden!
    pause
    exit /b
)

:: -------------------------------
:: Datenbank erstellen
:: -------------------------------
echo Erstelle Datenbank 'media_library'...
"%XAMPP_PATH%\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS media_library;"

:: -------------------------------
:: Initial-SQL importieren
:: -------------------------------
echo Importiere Initial SQL ...
"%XAMPP_PATH%\mysql\bin\mysql.exe" -u root media_library < "%SQL_PATH%db_schema.sql"

:: -------------------------------
:: Fertig
:: -------------------------------
echo Setup abgeschlossen!
pause
