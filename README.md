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
use Colorium\Template\Templater;

$templater = new Templater;
echo $templater->render('awesome', ['name' => 'you']); // Hello you !
```

## Settings

Setup root directory :

```php
$templater->directory = __DIR__ . '/../views/';
```

Setup file extension (default: `.php`) :

```php
$templater->suffix = '.phtml';
```

### Helpers

An helper is an inner sandboxed function, only usable in template.

```php
$templater->helpers['hi'] = function($name)
{
    return 'Hi ' . $name . ' !'; 
};
```

```php
# awesome.php
<html>
    <head></head>
    <body>
        <h1><?= self::hi($name) ?></h1>
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

### Blocks

You can define accessible blocks in the layout using `block($name)`:
```php
# mylayout.php
<html>
    <head></head>
    <body>
        <nav>
            <?php self::block('breadcrumb') ?>
            Homepage
            <?php self::end() ?>
        </nav>
        
        <?= self::content() ?>
    </body>
</html>
```

And change it from the template using `rewrite($name)` :
```php
# awesome.php
<?php self::layout('mylayout') ?>

<?php self::rewrite('breadcrumb') ?>
Homepage > Awesome
<?php self::end() ?>

<h1>Hello !</h1>
```

Result :
```php
# mylayout.php
<html>
    <head>
        <title></title>
    </head>
    <body>
        <nav>
            Homepage > Awesome
        </nav>
        
        <h1>Hello !</h1>
    </body>
</html>
```

## Install

`composer require colorium/templating`