<?php

namespace EsTeh\View\Compilers\Tea\Components;

use EsTeh\View\Compilers\CompilerComponent;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Compilers\Tea\Components
 * @license MIT
 */
class Braces extends CompilerComponent
{
	public function compile()
	{
		$this->compileEFunc();
		$this->compileRawExpression();
	}

	private function compileEFunc()
	{
		if (preg_match_all("/{{{(.*)}}}|{{(.*)}}/U", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $key => $val) {
				$this->strFile = str_replace($val, "<?php echo e({$matches[2][$key]}); ?>", $this->strFile);
			}
		}
	}

	private function compileRawExpression()
	{
		if (preg_match_all("/{!!(.*)!!}/i", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $key => $val) {
				$this->strFile = str_replace($val, "<?php echo {$matches[1][$key]}; ?>", $this->strFile);
			}
		}
	}
}
