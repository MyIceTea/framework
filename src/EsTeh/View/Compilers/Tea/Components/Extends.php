<?php

namespace EsTeh\View\Compilers\Tea\Components;

use EsTeh\View\Compilers\CompilerComponent;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Compilers\Tea\Components
 * @license MIT
 */
class Extends extends CompilerComponent
{
	public function compile()
	{
		$this->compileExtends();
		$this->compileRawExpression();
	}

	private function compileExtends()
	{
		if (preg_match_all("/@extends('(.*)')/U", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $key => $val) {
				$this->strFile = str_replace($val, "<?php echo view({$matches[2][$key]}); ?>", $this->strFile);
			}
		}
	}
}
