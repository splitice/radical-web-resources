<?php
namespace Radical\Web\Resource\CSS;
use Radical\Utility\HTML\Tag;

class Library extends Tag\Link {
	function __construct($library,$version = null){
		$this->attributes['href'] = $library;
	}
}