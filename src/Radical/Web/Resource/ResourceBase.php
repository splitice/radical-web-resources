<?php
namespace Radical\Web\Resource;

use Radical\Core\Server;
use Radical\Web\ResourceConfig;

abstract class ResourceBase {
	const PATH = '|';
	
	static function path($name){
		global $BASEPATH;
		return $BASEPATH.DIRECTORY_SEPARATOR.static::PATH.DIRECTORY_SEPARATOR.$name;
	}
	static function fileTime($name){
		$filemtime = 0;
		foreach(glob(static::Path($name)) as $dirs){
			foreach(glob($dirs.DIRECTORY_SEPARATOR.'*') as $file){
				$filemtime = max($filemtime, filemtime($file));
			}
		}
		return $filemtime;
	}
	static function exists($name){
		return file_exists(static::Path($name));
	}
	protected static abstract function _HTML($path);
	static function HTML($name){
        $path = Server::getSiteRoot();
        if((ResourceConfig::$addCachePrefixJs && static::PATH == 'js') || (ResourceConfig::$addCachePrefixCss && static::PATH == 'css')){
            $path .= ResourceConfig::$addCachePrefix.'/';
        }
        $path .= $name.'.'.static::FileTime($name).'.'.static::PATH;

		return static::_HTML($path);
	}
}