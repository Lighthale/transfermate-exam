# PHP Test Project

---
Author: **_Bryan Mangulabnan_** _(mangulabnan.bryan@ymail.com)_

Description: This project is my technical exam for _TransferMate_. Where there is a cml parser feature to parse XML files into the database (parse-xmls.php) and show the parsed data to the search feature (http://localhost:9000/public)

---

## Tools:
- Docker
- PHP 8.3
- PostreSQL 17.2

---

## Installation
1. `docker-compose up -d --build` will take a while for the app to build on the first run.
2. Once the build completes and the containers are running, create the database tables by going inside the db container `docker exec -it transfermate-exam-db sh` access the database `psql -U db_user -h localhost -d db_name` then run the commands inside `create-tables.sql` file then exit the container once done.
3. Now we have to populate the database tables some values. Run the php script to parse the xml files (inside of `/app/startfolder`). go inside the app container `docker exec -it transfermate-exam-app sh` then run `php /script/parse-xmls.php` then exit the container once done.
4. Open your browser then go to `http://localhost:9000/public`

--- 

## Limitations
* Search results doesn't have pagination.
* Search max results was fixed to 10 to cover up the CSS limitations (can't assign dynamic values for the delay count)