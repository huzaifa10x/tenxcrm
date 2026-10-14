# Tests

The suite uses a separate MySQL database, `tenx_test` (set in `phpunit.xml`), and wraps each
test in a transaction that is rolled back. It refuses to run against any database whose name
does not end in `_test`.

The repository's migrations can't build a database from scratch, so the test database is a
schema copy of the app database (once, and again after new migrations):

```bash
mysql -e "DROP DATABASE IF EXISTS tenx_test; CREATE DATABASE tenx_test"
mysqldump --no-data YOUR_DB | mysql tenx_test
mysqldump --no-create-info YOUR_DB migrations | mysql tenx_test

php vendor/bin/phpunit
```
