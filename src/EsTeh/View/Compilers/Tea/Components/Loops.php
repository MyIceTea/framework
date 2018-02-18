<?php

namespace EsTeh\View\Compilers\Tea\Components;

use EsTeh\View\Compilers\CompilerComponent;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @package \EsTeh\View\Compilers\Tea\Components
 * @license MIT
 */
class Loops extends CompilerComponent
{
	public function compile()
	{
		$this->compileForeach();
		$this->compileFor();
		$this->compileDoWhile();
		$this->compileWhile();
	}

	private function compileForeach()
	{
		if (preg_match_all("/@\s?foreach\s?\(.+as.+\)/Ui", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $val) {
				$this->strFile = str_replace($val, "<?php ".substr($val, 1).": ?>", $this->strFile);
			}
		}
		$this->strFile = str_replace("@endforeach", "<?php endforeach; ?>", $this->strFile);
	}

	private function compileFor()
	{
		if (preg_match_all("/@\s?for\s?\(.+;.+;.+\)/Ui", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $val) {
				$this->strFile = str_replace($val, "<?php ".substr($val, 1).": ?>", $this->strFile);
			}
		}
		$this->strFile = str_replace("@endfor", "<?php endfor; ?>", $this->strFile);
	}

	private function compileDoWhile()
	{
		if (preg_match_all("/@\s?do(.+)@\s?while\s?\((.+)\)/Uis", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $key => $val) {
				$this->strFile = str_replace($val, "<?php do { ?>{$matches[1][$key]}<?php } while({$matches[2][$key]}); ?>", $this->strFile);
			}
		}
	}

	private function compileWhile()
	{
		if (preg_match_all("/@\s?while\s?\(.+\)/Ui", $this->strFile, $matches)) {
			$matches[0] = array_unique($matches[0]);
			foreach ($matches[0] as $val) {
				$this->strFile = str_replace($val, "<?php ".substr($val, 1).": ?>", $this->strFile);
			}
		}
		$this->strFile = str_replace("@endwhile", "<?php endwhile; ?>", $this->strFile);
	}
}
