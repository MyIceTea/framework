<?php

print shell_exec("rm -rfv src/*");

$exceptions = ["..", ".", "framework"];
foreach (scandir($path = realpath("..")) as $key => $val) {
	if (! in_array($val, $exceptions)) {
		print shell_exec("cp -rfv {$path}/{$val} ".($cur = __DIR__."/src/".ucfirst($val)));
		print shell_exec("rm -rf {$cur}/.git");
		print shell_exec("rm -rfv {$cur}/LICENSE");
	}
}
