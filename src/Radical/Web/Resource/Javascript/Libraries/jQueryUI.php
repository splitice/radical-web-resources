<?php
namespace Radical\Web\Resource\Javascript\Libraries;
use Radical\Web\Resource\Shared;

class jQueryUI extends Shared\LibraryBase implements IJavascriptLibrary {
	const URL = 'https://ajax.googleapis.com/ajax/libs/jqueryui/%(version)s/jquery-ui.min.js';
	
	function __construct($version = '1.8.13'){
		$version = $version ? $version : '1.8.13';
		if(is_float($version)){
			$version = (string)$version;
			$version .= '.0';
		}
		$this->depends['jQuery'] = 'jQuery';
		parent::__construct($version);
	}
}