# MediaWiki API

A basic PHP MediaWiki API to get pages and files

Get page content or URL:
```php
$api = new Api('https://de.wikipedia.org');
$page = $api->getPage('Geschichte der Kölner Straßenbahn');
$content = $page->getContent();
$url = $page->getUrl();
```

Get image user and page url:
```php
$api = new Api('https://de.wikipedia.org');
$image = $api->getImage('File:KVB4076_Severinsbruecke.jpg');
$pageUrl = $image->getPageUrl();
$user = $image->getImageUser();
```

Get image URL in a certain size:
```php
$api = new Api('https://de.wikipedia.org');
$image = $api->getImage('File:KVB4076_Severinsbruecke.jpg');
$url = $image->getImageUrl(1920);
```
If the original image is smaller than the requested size, the URL of the original is returned.