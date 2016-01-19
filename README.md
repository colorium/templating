# Easy PHP template engine

## Basic usage

```php
# awesome.php
<html>
    <head></head>
    <body>
        <h1>Hello <?= $name ?> !</h1>
    </body>
</html>
```

```php
use Colorium\Templating\Engine;

$engine = new Engine;
echo $engine->render('awesome', ['name' => 'you']); // Hello you !
```

## Settings

Setup root directory :

```php
$engine->directory = __DIR__ . '/../views/';
```

Setup file extension (default: `.php`) :

```php
$engine->suffix = '.phtml';
```

### Helpers

An helper is an inner sandboxed function, only usable in template.

```php
$engine->helpers['sayhi'] = function($name)
{
    return 'Hi ' . $name . ' !'; 
};
```

```php
# awesome.php
<html>
    <head></head>
    <body>
        <h1><?= self::sayhi($name) ?></h1>
    </body>
</html>
```

### Layout

You can set a layout for the current template (and pass some data) :


```php
# awesome.php
<?php self::layout('mylayout', ['title' => 'Awsome Page']) ?>

<h1>Hello <?= $name ?> !</h1>
```

In the layout file, define where to place the template content :

```php
# mylayout.php
<html>
    <head>
        <title><?= $title ?></title>
    </head>
    <body>
        <?= self::content() ?>
    </body>
</html>
```

### Sections

You can change some layout blocks using sections :

```php
# awesome.php
<?php self::layout('mylayout', ['title' => 'Awsome Page']) ?>

<h1>Hello <?= $name ?> !</h1>

<?php self::section('css') ?>
<link rel='stylesheet' type='text/css' href='/css/awesome.css' />
<?php self::end() ?>
```

```php
# mylayout.php
<html>
    <head>
        <title><?= $title ?></title>
        <?= self::insert('css') ?>
    </head>
    <body>
        <?= self::content() ?>
    </body>
</html>
```

## Install

`composer require colorium/templating`