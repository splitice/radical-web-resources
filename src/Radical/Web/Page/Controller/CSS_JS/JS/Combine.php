<?php
namespace Radical\Web\Page\Controller\CSS_JS\JS;

use Radical\Web\Page\Controller\CSS_JS\Internal\CombineBase;

class Combine extends CombineBase {
	const EXTENSION = 'js';
	const MIME_TYPE = 'text/javascript';
	
	function optimize($code){
		return $code;
		//return Optimiser\Javascript\JSMin::minify($ret);
	}
}