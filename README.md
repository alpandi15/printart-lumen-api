## Installation

Following this installation:

```sh
git clone https://gitlab.com/printartindonesia/printart_apis_accurate5.git
composer install
```

**Database**
- Create new database mysql

**Environment**
- Create file `.env` in this folder
- Copy from `.env.example` to `.env`
- Follow this code in `.env`

```
API_VERSION=/api/v1

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<new_database>
DB_USERNAME=<user_db>
DB_PASSWORD=<password_db>

DB_ACCURATE=<path_your_accurate_database>
DB_ACCURATE_HOST=<host_accurate_database>
DB_ACCURATE_USERNAME=<user_accurate_database>
DB_ACCURATE_PASSWORD=<password_accurate_database>

EXTERNAL_AUTH=http://<external_app_auth_get_userdata_by_token>

TYPE_NUMBER=E <number_type_invoice>
```

**Migrate Database**

After change `.env` file

```sh
php artisan migrate
php artisan db:seed
```


**Running**

```sh
php -S localhost:8000 -t public
```

## Documentation

```
http://<host>:<port>/api/documentation
```

## SOURCE PROCEDURE DB ACCURATE

GET ARINVID in tbl ARINV

~~~~sql
SELECT GEN_ID(ARINV_GEN, 1 ) FROM RDB$DATABASE;
EXECUTE PROCEDURE GETARINV_ID_NO;
~~~~

GET ARINVDET (ARINVID, SEQ)

~~~~sql
EXECUTE PROCEDURE GET_ARINVDET(6156, 1);
~~~~

GET TRANSACTIONID in tbl TRANSHISTORY

~~~~sql
SELECT GEN_ID(HISTORYTRANSACTION_GEN, 1 ) FROM RDB$DATABASE;
EXECUTE PROCEDURE ADDTRANSACTIONSHITORY(45, 1, '', 1, NULL, 0);
~~~~

GET ID tbl PERSONDATA

~~~~sql
SELECT GEN_ID(PERSONDATA_GEN, 1) FROM RDB$DATABASE; 
EXECUTE PROCEDURE GETPERSONID;
~~~~

GET SALESMANID tbl SALESMAN

~~~~sql
SELECT GEN_ID(SALESMAN_GEN, 1) FROM RDB$DATABASE;
EXECUTE PROCEDURE GETSALESMANID;
~~~~

GET USERID tbl USERS

~~~~sql
SELECT GEN_ID(USER_GEN, 1) FROM RDB$DATABASE;
EXECUTE PROCEDURE GETUSERID;
~~~~