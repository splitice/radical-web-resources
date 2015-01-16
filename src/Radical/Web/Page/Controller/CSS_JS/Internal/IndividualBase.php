<?php
namespace Radical\Web\Page\Controller\CSS_JS\Internal;

abstract class IndividualBase extends \Radical\Web\Page\Handler\PageBase {
	protected $name;

	const MIME_TYPE = 'text/plain';
	const EXTENSION = '';
	
	//TODO: split css and js
	private static $ext_handlers = array();
	
	protected function get_contents($file){
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		$ext = strtolower($ext);
		if(isset(self::$ext_handlers[$ext])){
			$c = self::$ext_handlers[$ext];
			return $c($file);
		}
		
		return file_get_contents($file);
	}
	
	public static function register_handler($ext, $callback){
		self::$ext_handlers[$ext] = $callback;
	}
	
	function __construct($data){
		$this->name = $data['name'];
	}
	protected function sendHeaders($file){
		if(!is_array($file)){
			$file = array($file);
		}
		
		$headers = \Radical\Web\Page\Handler::current()->headers;
		$headers->Add('Content-Type',static::MIME_TYPE);
		$headers->Add('Cache-Control','public');
		$headers->setCache(60*60*24);
		$headers->Add('Pragma','cache');
		//$headers['Vary'] = 'Accept-Encoding';

        $times = array_map('filemtime',$file);
        if(!$times){
            return;
        }
		$filemtime = max($times);
		//die(var_dump($file));
		$headers->setLastModified($filemtime);
	}
	protected function getPath(){
		return $this->name;
	}
	private function getFile(){
		global $BASEPATH;
		return rtrim($BASEPATH,"/").'/'.ltrim($this->getPath(),'/');
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
	}
}