<?php

/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nextras\YoutubeApi;

use Nette;



class Reader extends Nette\Object
{

	/**
	 * Fetchs video data
	 * @param  string  $videoId
	 * @return Video
	 */
	public function getVideo($videoId)
	{
		$data = $this->getData($videoId);
		if (!$data)
			return FALSE;

		return $this->parseData($data, $videoId);
	}



	protected function getData($videoId)
	{
		$url = "http://gdata.youtube.com/feeds/api/videos/{$videoId}";
		$content = @file_get_contents($url);
		if (!$content)
			return FALSE;

		return $content;
	}



	protected function parseData($data, $videoId)
	{
		$doc = new \DOMDocument();
		$doc->loadXML($data);

		$xpath = new \DOMXPath($doc);
		$video = new Video;

		$title = $xpath->query('//media:title/text()')->item(0);
		$video->title = $title->wholeText;

		$description = $xpath->query('//media:description/text()')->item(0);
		if ($description)
			$video->description = $description->wholeText;

		$video->url = 'http://www.youtube.com/watch?v=' . $videoId;

		$thumbs = $xpath->query('//media:thumbnail');
		foreach ($thumbs as $thumb) {
			$video->thumbs[] = (object) array(
				'url'    => $thumb->getAttribute('url'),
				'time'   => $thumb->getAttribute('time'),
				'width'  => $thumb->getAttribute('width'),
				'height' => $thumb->getAttribute('height'),
			);
		}

		return $video;
	}

}
