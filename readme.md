Nextras YoutubeApi
==================

[![Downloads this Month](https://img.shields.io/packagist/dm/nextras/youtube-api.svg?style=flat)](https://packagist.org/packages/nextras/youtube-api)
[![Stable version](http://img.shields.io/packagist/v/nextras/youtube-api.svg?style=flat)](https://packagist.org/packages/nextras/youtube-api)

### Installation

Use composer:

```bash
$ composer require nextras/youtube-api
```

### Usage


```php
$reader = new Nextras\YoutubeApi\Reader('<your google-api key>')
$video = $reader->getVideoByUrl('<youtube url>');

echo $video->title;
echo $video->duration; // in sec
echo $video->description;
echo $video->url;
foreach ($video->thumbs as $thumb) {
    echo $video->url; 
}
```

### License

MIT. See full [license](license.md).
