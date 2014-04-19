<?php
namespace Radical\Web\Page\Router\Recognisers;

use Radical\Utility\Net\URL;
use Radical\Web\Page\Router\IPageRecognise;
use Radical\Web\Page\Controller;

class Special implements IPageRecognise {
	static function recognise(URL $url){
		$url = $url->getPath();
		$path = $url->getPath(true);
		$ext = pathinfo($path,PATHINFO_EXTENSION);
		if($ext=='css'){
			if($url->firstPathElement() == 'css'){//Direct access dont combine
				$url->removeFirstPathElement();
				return new Controller\CSS_JS\CSS\Individual(array('name'=>(string)$path));
			}else{
				$name = substr($path,1,-4);
				return new Controller\CSS_JS\CSS\Combine(array('name'=>$name));
			}
		}
		if($ext=='js'){
			if($url->firstPathElement() == 'js'){//Direct access dont combine
				$url->removeFirstPathElement();
				return new Controller\CSS_JS\JS\Individual(array('name'=>(string)$path));
			}else{
				$name = substr($path,1,-3);
				return new Controller\CSS_JS\JS\Combine(array('name'=>$name));
			}
		}
	}
}