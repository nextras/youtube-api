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

use DateInterval;
use GuzzleHttp;
use Kdyby\CurlCaBundle\CertificateHelper;
use Nette;


class Reader extends Nette\Object
{
	/** @var string */
	private $apiKey;

	/** @var string */
	protected $youtubeFetchUrl = 'https://www.googleapis.com/youtube/v3/videos?key=%s&part=snippet,contentDetails&id=%s';


	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}


	/**
	 * Fetchs video data by youtube url
	 * @param  string  youtube url
	 * @return Video
	 */
	public function getVideoByUrl($videoUrl)
	{
		$url = new Nette\Http\Url($videoUrl);

		if (stripos($url->host, 'youtu.be') !== FALSE) {
			return $this->getVideo(trim($url->getPath(), '/'));
		}

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
		return $this->parseData($this->getData($videoId), $videoId);
	}



	protected function getData($videoId)
	{
		$url = sprintf($this->youtubeFetchUrl, $this->apiKey, $videoId);
		$client = new GuzzleHttp\Client;
		$response = $client->request('GET', $url, ['verify' => CertificateHelper::getCaInfoFile(), 'http_errors' => FALSE]);

		if ($response->getStatusCode() !== 200) {
			throw new \RuntimeException("Unable to parse YouTube video: '{$videoId}' ({$response->getStatusCode()}) {$response->getReasonPhrase()}");
		}

		return $response->getBody()->getContents();
	}



	protected function parseData($data, $videoId)
	{
		$data = Nette\Utils\Json::decode($data);
		if (!isset($data->items[0]->snippet) || !isset($data->items[0]->contentDetails)) {
			throw new \RuntimeException("Empty YouTube response, probably wrong '{$videoId}' video id.");
		}

		$snippet = $data->items[0]->snippet;
		$details = $data->items[0]->contentDetails;

		$video = new Video;

		$video->title = $snippet->title;
		$video->description = $snippet->description;
		$video->url = 'http://www.youtube.com/watch?v=' . $videoId;

		$interval = new DateInterval($details->duration);
		$video->duration = $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;

		foreach (['default', 'medium', 'high', 'standard', 'maxres'] as $type) {
			if (isset($snippet->thumbnails->$type)) {
				$video->thumbs[$type] = $snippet->thumbnails->$type;
			}
		}

		return $video;
	}

}
