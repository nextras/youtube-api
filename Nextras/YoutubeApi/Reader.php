<?php

/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * Copyright (c) 2012 Jan Skrasek (http://jan.skrasek.com)
 *
 * @license    MIT
 * @link       https://github.com/nextras
 */

namespace Nextras\YoutubeApi;

use Nette;



class Reader extends Nette\Object
{

	/**
	 * Fetchs video data by youtube url
	 * @param  string  youtube url
	 * @return Video
	 */
	public function getVideoByUrl($videoUrl)
	{
		$url = new Nette\Http\Url($videoUrl);
		$params = $url->query;
		parse_str(urldecode($params), $params);
		if (stripos($url->host, 'youtube.com') === FALSE || empty($params['v'])) {
			throw new Nette\InvalidArgumentException('videoUrl must be valid youtube url.');
		}

		return $this->getVideo($params['v']);
	}



	/**
	 * Fetchs video data
	 * @param  string  video id
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

		$duration = $xpath->query('//yt:duration')->item(0);
		$video->duration = (int) $duration->getAttribute('seconds');

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
