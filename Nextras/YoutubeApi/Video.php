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



class Video extends Nette\Object
{

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

}
