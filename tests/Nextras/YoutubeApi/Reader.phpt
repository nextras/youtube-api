<?php

/**
 * Test: Nette\YoutubeApi\Reader.
 *
 * @author     Jan Skrasek
 * @package    Nextras\YoutubeApi
 * @subpackage UnitTests
 */

require_once __DIR__ . '/../bootstrap.php';



$reader = new Nextras\YoutubeApi\Reader;
$video  = $reader->getVideo('wsaPIG6kvlo');

Assert::true($video instanceof Nextras\YoutubeApi\Video);
Assert::same('Nette Framework and Flash Messages', $video->title);
Assert::same('http://www.youtube.com/watch?v=wsaPIG6kvlo', $video->url);
Assert::null($video->description);

Assert::same(4, count($video->thumbs));
Assert::true(!empty($video->thumbs[0]->url));
Assert::true(!empty($video->thumbs[1]->width));
Assert::true(!empty($video->thumbs[2]->height));
Assert::true(!empty($video->thumbs[3]->time));



$video2 = $reader->getVideoByUrl($video->url);
Assert::equal($video, $video2);
