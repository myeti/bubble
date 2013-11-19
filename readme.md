# This is Bubble !!

The easiest way to make static html website with layout and nice urls.

## Quick start

### First, create your **Bubble**

*(or just copy this project)*

```php
# index.php

require 'Bubble.php';

$bubble = new Bubble();
$bubble->pop();
```

### Then, create your pages

All templates are stored in `pages/` folder. When **Bubble** calls a template, its content will be displayed in the layout `pages/_layout.php`;

### Finally, go to your app in your browser

Bubble will search the templates in the folder `pages/` depending on the url : for the query `/welcome`, the template called will be `/pages/welcome.html`.

Try it... Yeah it works ;)

## Extra

### Adding vars to the template

```php
$bubble->gum('welcome', [
    'today' => date('Y-m-d')
]);
```

And, in the template `pages/welcome.php` :

```php
<aside>Today : <?= $today ?></aside>
```

### Default paths

You can change the default path for the root dir, the layout, the landing page and the 404 :

```php
$bubble = new Bubble([
    'path.dir'      => 'another-dir/',  // default : pages/
    'path.layout'   => 'my.layout.php', // default : _layout.php
    'path.index'    => 'landing.html',  // default : index.html
    'path.404'      => 'notfound.html'  // default : 404.html
]);
```

### That's all :)

*Bubble is under the DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE*