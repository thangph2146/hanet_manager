@echo off
REM Script to run seeders for modules
REM Usage: run-seeder.bat [moduleName] [seederName]

if "%1"=="" (
    echo Please provide a module name
    exit /b 1
)

if "%2"=="" (
    echo Please provide a seeder name
    exit /b 1
)

php spark db:seed:module %1 %2 