<?php
namespace Radical\Web\Resource\Javascript\Libraries;
use Radical\Web\Resource\Shared;

class jQueryMobile extends Shared\LibraryBase implements IJavascriptLibrary {
	const URL = 'http://ajax.aspnetcdn.com/ajax/jquery.mobile/%(version)s/jquery.mobile-%(version)s.min.js';
	
	function __construct($version = 1.1){
		$version = $version ? $version : 1.1;
		if(is_float($version)){
			$version = (string)$version;
			$version .= '.0';
		}
		$this->depends['jQuery'] = 'jQuery';
		parent::__construct($version);
	}
}