<?php
namespace Radical\Web\Page\Controller\CSS_JS\Internal;

abstract class IndividualBase extends \Web\Page\Handler\PageBase {
	protected $name;

	const MIME_TYPE = 'text/plain';
	const EXTENSION = '';
	
	function __construct($data){
		$this->name = $data['name'];
	}
	protected function sendHeaders($file){
		if(!is_array($file)){
			$file = array($file);
		}
		
		$headers = \Web\Page\Handler::current()->headers;
		$headers->Add('Content-Type',static::MIME_TYPE);
		$headers->Add('Cache-Control','public');
		$headers->setCache(60*60*24);
		$headers->Add('Pragma','cache');
		$headers['Vary'] = 'Accept-Encoding';
		
		$filemtime = max(array_map('filemtime',$file));
		//die(var_dump($file));
		$headers->setLastModified($filemtime);
	}
	protected function getPath(){
		return $this->name;
	}
	private function getFile(){
		global $BASEPATH;
		$expr = $BASEPATH.'{system,app}'.DS.$this->getPath();
		return array_pop(glob($expr,GLOB_BRACE ));
	}
	/**
	 * Handle GET request
	 *
	 * @throws \Exception
	 */
	function GET(){
		$file = $this->getFile();
		$this->sendHeaders($file);
		$ret = file_get_contents($file);
		
		echo $ret;
		//return new \Page\Handler\GZIP($ret);
	}
}