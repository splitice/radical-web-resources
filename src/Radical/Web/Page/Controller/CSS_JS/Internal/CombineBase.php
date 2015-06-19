<?php
namespace Radical\Web\Page\Controller\CSS_JS\Internal;
use Radical\Cache\PooledCache;
use Radical\Web\Page\Controller\CSS_JS\CSS\Individual;

abstract class CombineBase extends IndividualBase {
	protected $version;
	
	const EXTENSION = '';
	const MIME_TYPE = 'text/plain';
	
	function __construct($data){
		parent::__construct($data);
		$n = $data['name'];
		$pos = strrpos($n,'.');
		if($pos){
			$this->version = ((int)substr($n,$pos+1))^6;
			$n = substr($n,0,$pos);
		}
		$this->name = $n;
	}
	static function link($name){
        $cache = PooledCache::Get('resource_'.static::EXTENSION, 'Memory');
		
		$version = (int)$cache->Get($name);
		if(!$version){
			$path = new \Radical\Core\Resource(static::EXTENSION.DS.$name);
			foreach($path->getFiles() as $f){
				$version = max($version,filemtime($f));
			}
			$cache->Set($name, $version, 10);
		}
		
		return '/'.$name.'.'.$version.'.'.static::EXTENSION;
	}
	static function exists($name){
		$path = new \Radical\Core\Resource(static::EXTENSION.DS.$name);
		return $path->exists();
	}
	protected function getPath(){
		return static::EXTENSION.DS.parent::getPath();
	}
	protected function getFiles($expr = '*'){
		$path = new \Radical\Core\Resource($this->getPath());
		return $path->getFiles($expr);
	}
	protected function sendHeaders(){
		parent::sendHeaders($this->getFiles());
	}
	function optimize($code){
		return \CssMin::minify($code);
	}
	/**
	 * Handle GET request
	 *
	 * @throws \Exception
	 */
	function GET(){
		$key = static::EXTENSION.'_'.$this->name.'_'.$this->version;
		
		$this->sendHeaders();
		$cache = PooledCache::Get(get_called_class(), 'Memory');
		
		$ret = $cache->get($key);
		
		if(!$ret || !\Radical\Core\Server::isProduction()){
			$data = array();
			$files = $this->getFiles();
			foreach($files as $f){
				if(is_file($f)){//Ignore folders
					$fn = basename($f);
					
					$data[$fn] = Individual::get_file($f);
				}
			}
			
			$ret = '';
			foreach($data as $f=>$d){
				if(!\Radical\Core\Server::isProduction()){
					$ret .= "\r\n/* Including: ".$f." */\r\n";
				}
				$ret .= $d;
			}
			
			if(\Radical\Core\Server::isProduction()){
				$ret = $this->optimize($ret);
				$cache->set($key, $ret);
			}
		}
		
		echo $ret;
		
		$headers = \Radical\Web\Page\Handler::top()->headers;
		$headers->setContentLength(strlen($ret));
		$headers['Vary'] = 'Accept-Encoding';
	}
}