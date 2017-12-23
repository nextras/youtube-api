<?php

/**
 * This file is part of the YoutubeApi library.
 * @license    MIT
 * @link       https://github.com/nextras/youtube-api
 */

namespace Nextras\YoutubeApi;

use Nette;


class Video
{
	use Nette\SmartObject;

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	/** @var string */
	public $url;

	/** @var int */
	public $duration;

	/** @var array */
	public $thumbs;

	/** @var string */
	public $embed;
}
