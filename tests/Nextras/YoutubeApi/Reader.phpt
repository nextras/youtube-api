<?php

/**
 * Test: Nette\YoutubeApi\Reader.
 *
 * @author     Jan Skrasek
 * @package    Nextras\YoutubeApi
 * @subpackage UnitTests
 */

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


$config = parse_ini_file(__DIR__ . '/../../php.ini');
$apiKey = $config['googleApiKey'];

$reader = new Nextras\YoutubeApi\Reader($apiKey);
$video = $reader->getVideo('wsaPIG6kvlo');

Assert::true($video instanceof Nextras\YoutubeApi\Video);
Assert::same('Nette Framework and Flash Messages', $video->title);
Assert::same('http://www.youtube.com/watch?v=wsaPIG6kvlo', $video->url);
Assert::same(' ', $video->description);
Assert::same(335, $video->duration);

Assert::same(3, count($video->thumbs));

// check only basic
foreach (['default', 'medium', 'high'] as $type) {
	Assert::true(!empty($video->thumbs[$type]->url));
	Assert::true(!empty($video->thumbs[$type]->width));
	Assert::true(!empty($video->thumbs[$type]->height));
}

$video2 = $reader->getVideoByUrl($video->url);
Assert::equal($video, $video2);

Assert::throws(function () use ($reader) {
	$reader->getVideo('notExistYouTubeCode');
}, RuntimeException::class, "Empty YouTube response, probably wrong 'notExistYouTubeCode' video id.");
