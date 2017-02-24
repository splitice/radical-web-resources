<?php
namespace Radical\Web\Resource\Shared;
use Radical\Basic\StringHelper;

abstract class LibraryBase {
	const URL = '';
	protected $path;
	public $depends = array();
	
	function __construct($version){
		$this->path = StringHelper\Format::sprintfn(static::URL,compact('version'));
	}
	
	function __toString(){
		return $this->path;
	}
}