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
If you´re using the Laravel framework, you all you  have to do is reference the service provider and you are ready to go !
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

$nav = new Navigation();

```

> In case of using Laravel Navigation is shared via the IoC Container, so if you hydrate a bucket once, the bucket will be available all over your app.

#### Basic usage
First step is always hydrating the bucket. To do so, you have to provide an array of objects (preferably \StdClass)
with the following properties:
- `itemId` unique identifier like the database index
- `itemContent` the content that should be rendered for this item
- `parentId` identifier of the parent
- `uri` to be able to renderMenu this item as a link you have to provide a uri

In the `fill` method you are able to rename each parameter to your corresponding properties :

```js
// example.json
[
    {
        "id": 1,
        "title": "Books",
        "parent": null,
        "uri": "books"
    },
    {
        "id": 2,
        "title": "Fiction",
        "parent": 1,
        "uri": "books/fiction"
    }
]

```
> Please note the different naming of the `itemContent` as `title` !

```php
// format json to object-array
$data = json_decode(file_get_contents('example.json'));

// with Laravel
Navigation::bucket()->hydrate($data, $itemIdentifier='id', $itemContent='title',$parentIdentifier='parent',$uriField = 'uri');

//without Laravel
$nav->bucket()->hydrate($data, $itemIdentifier='id', $itemContent='title',$parentIdentifier='parent',$uriField = 'uri');
```


After hydrating you are able to generate the desired HTML list for the menu:
```php

//with Laravel
Navigation::menu('main')->subNav('Books')->renderMenu();

//without Laravel
$nav->menu('main')->subNav('Books')->renderMenu();

```
For the breadcrumbs you just call the same bucket:
```php
//with Laravel
Navigation::breadcrumbs()->pathTo('Fiction')->renderMenu();

//without Laravel
$nav->breadcrumbs()->pathTo('Fiction')-renderMenu();
```









