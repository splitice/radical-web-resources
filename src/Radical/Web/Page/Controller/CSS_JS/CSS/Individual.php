<?php

namespace Radical\Web\Page\Controller\CSS_JS\CSS;

use Radical\Web\Page\Controller\CSS_JS\Internal\IndividualBase;

class Individual extends IndividualBase {
	const MIME_TYPE = 'text/css';
	const EXTENSION = 'css';
	static function loadCallback($file, $parser) {
		$paths = array ();
		foreach ( $parser->extensions as $extensionName ) {
			$namespace = ucwords ( preg_replace ( '/[^0-9a-z]+/', '_', strtolower ( $extensionName ) ) );
			$extensionPath = './' . $namespace . '/' . $namespace . '.php';
			if (file_exists ( $extensionPath )) {
				require_once ($extensionPath);
				$hook = $namespace . '::resolveExtensionPath';
				$returnPath = call_user_func ( $hook, $file, $parser );
				if (! empty ( $returnPath )) {
					$paths [] = $returnPath;
				}
			}
		}
		return $paths;
	}
	
	static function get_file($file) {
		return parent::get_contents($file);
	}
}