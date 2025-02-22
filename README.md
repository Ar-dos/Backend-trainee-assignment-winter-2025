Перед запуском docker нужно в папке с исходным кодом (src) скопировать файл .env.example в ту же папку как файл .env .
В этом же файле требуется указать в строке DB_HOST=127.0.0.1 ip адрес устройства.
Также в папке srс нужно запустить команду composer install для установки зависимостей.
После этого можно запускать docker командой docker-compose up -d.
При работе docker http запросы будут приниматься по адресу localhost:8080.

Пример использования jwt токена в хедере http-запроса: { Authorization : Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXJhdmVsIiwiaWF0IjoxNzM5Njg3NjUxLCJleHAiOjE3Mzk2OTMwNTEsImp0aSI6IklncW16OE9JdWQwNkJtZ1oiLCJpZCI6MX0.xigK_n3sz0MgrCcVQOpIARUL-D--EONOfKjWc2t3pQw } .
