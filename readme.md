[![Build Status](https://travis-ci.org/bastiankoetsier/navigation.svg?branch=master)](https://travis-ci.org/bastiankoetsier/navigation)
# Navigation
After filling the navigation with your menu / breadcrumb - information (e.g. categories or whatever), you´re able to
create a fully rendered HTML list.

## Installation

Pull in the package with composer:
```js
{
    "require": {
                "bkoetsier/navigation": "dev-master"
                }
}
```

### Laravel user
If you´re using the Laravel framework, all you  have to do is reference the service provider and you are ready to go !
```php
// app/config/app.php

'providers' => [
    '...',
    'Bkoetsier\Navigation\Providers\Laravel\NavigationServiceProvider',
];
```

### Without Laravel
If you aren´t using Laravel, you have to wire up the package yourself:
```php
use Bkoetsier\Navigation\Navigation;
use Illuminate\Support\Collection;

$nav = new Navigation(new Bucket(new Collection));

```

> In case of using Laravel Navigation is shared via the IoC Container, so if you hydrate a bucket once, the bucket will be available all over your app,
> but the main difference is in instantiation

#### Basic usage
First step is always hydrating the bucket. To do so, you have to provide an array of objects (preferably `StdClass`)
with the following properties:
- `itemId` unique identifier like the database index
- `itemContent` the content that should be rendered for this item
- `parentId` identifier of the parent

In the `fill` method you are able to rename each parameter to your corresponding properties :

```js
// example.json
[
    {
        "id": 1,
        "content": '<a href="/books">Books</a>',
        "parent": null,
    },
    {
        "id": 2,
        "content": '<a href="/books/fiction">Fiction</a>',
        "parent": 1,
    }
]

```

```php
// format json to object-array
$data = json_decode(file_get_contents('example.json'));

// with Laravel
Nav::fill($data, $itemIdentifier = 'id', $itemContent ='content',$parentIdentifier = 'parent');

//without Laravel
$nav->fill($data, $itemIdentifier = 'id', $itemContent = 'content',$parentIdentifier = 'parent');
```

After hydrating you have to set the current item-id and call the `render`-method:
```php
//with Laravel
// set the current active item-id, maybe from a url or db
Nav::setCurrent(2);
Nav::menu()->render();

//without Laravel
$nav->setCurrent(2);
$nav->menu()->render();
```
> Attention: when you use ```php Nav::setCurrent()``` it will be set for each menu you have defined !

will output:
```html
<ul>
    <li>
    <a href="/books">Books</a>
    <ul>
        <li>
        <span class="active"><a href="/books/fiction">Fiction</a></span>
        </li>
    </ul>
    </li>
</ul>
```
> Please note that the current item will be wrapped in a span.active for additional styling

> `Books` has a Level of `1` not `0` !

If you have multiple navigation on your site you can set different states for each one:
```php
Nav::menu('main')->setCurrent(1)->setMaxLevel(1);
Nav::menu('sub')->setCurrent(2);

//will render until level == 1 from id 1 down
Nav::menu('main')->render();
// will render from id 2 down until the end
Nav::menu('sub')->render();
```
For the breadcrumbs you just call the same bucket:
```php
//with Laravel
Nav::breadcrumbs()->render();

//without Laravel
$nav->breadcrumbs()->render();
```









