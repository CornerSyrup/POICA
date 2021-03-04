# Activate PostgreSQL with xampp

include following in `php.ini`

```ini
extension=php_pgsql.dll
```

## Confirmation

with calling `phpinfo();`, an extra section of pgsql should show up.
which require restart of server, after configuration of php.ini file.

```php
phpinfo();
```
