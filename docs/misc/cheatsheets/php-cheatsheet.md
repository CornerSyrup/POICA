# PHP Cheatsheet

## Output

Script style

```php
<?php
print($content);
echo $content;

```

Single-line style

```php
<?= $content ?>
```

## If statement

Script style

```php
<?php
if ($condition) {
    # Code Here ...
} elseif ($condition) {
    # Code Here ...
} else {
    # Code Here ...
}
```

Block style

```php
<?php if ($condition) : ?>
    # Code Here ...
<?php elseif ($condition) : ?>
    # Code Here ...
<?php else : ?>
    # Code Here ...
<?php endif; ?>
```

## While statement

script style

```php
<?php
while ($condition) {
    # Code Here ...
}
```

block style

```php
<?php while ($condition) : ?>
    # Code Here ...
<?php endwhile; ?>
```

## Do statement

script style

```php
<?php
do {
    # code...
} while ($condition);
```

## For statement

script style

```php
<?php
for ($i = $start; $i < $value; $i++) {
    # Code Here ...
}
```

block style

```php
<?php for ($i = $start; $i < $value; $i++) : ?>
    # Code Here ...
<?php endfor; ?>
```

## ForEach statement

script style

```php
<?php
foreach ($array as $key => $value) {
    # Code Here ...
}
```

block style

```php
<?php foreach ($array as $key => $value) : ?>
    # Code Here ...
<?php endforeach; ?>
```

## Switch statement

script style

```php
<?php switch ($value) {
    case 'value':
        # Code Here ...
        break;
    default:
        # Code Here ...
        break;
}
```

block style

```php
<?php switch ($value):
    case 1: ?>
        # Code Here ...
        <?php break; ?>
    <?php
    case 2: ?>
        # Code Here ...
        <?php break; ?>
<?php endswitch; ?>
```

## Documenting

```php
<?php

/**
 * Function comment
 *
 * @param integer $var
 * @return string
 */
function FunctionName(int $var = null): string
{
    # Code Here ...
    return '';
}

```
