@echo off
REM Script to run all seeders in the correct order

echo Running all seeders...

REM First run base seeders
echo Running loainguoidung seeders...
php spark db:seed:module quanlynguoidung NguoiDung

echo Running loaisukien seeders...
php spark db:seed:module quanlyloaisukien LoaiSuKien

REM Add more seeders as needed
REM echo Running additional seeders...
REM php spark db:seed:module somemodul SomeSeeder

echo All seeders completed successfully!
pause 