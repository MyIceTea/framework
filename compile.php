<?php
$gpgsec = "858869123";
$licenseFile = 'MIT License

Copyright (c) 2018 MyIceTea

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
';


file_put_contents('/tmp/qqq', $gpgsec);
compile('src/EsTeh', __DIR__ . '/../packages');
shell_exec('git config --global commit.gpgsign false');
commit(__DIR__.'/../packages', $licenseFile);
shell_exec('git config --global commit.gpgsign true');

function compile($dir, $makedir)
{
	if (! is_dir($makedir)) {
		mkdir($makedir);
	}
	if (! is_dir($makedir)) {
		throw new \Exception("Cannot create directory", 1);
	}
	$makedir = realpath($makedir);
	$r = [];
	$scan = scandir($dir);
	unset($scan[0], $scan[1]);
	foreach ($scan as $val) {
		if ($val !== '.' && $val !== '..') {
			$rval = $dir.'/'.$val;
			if (is_dir($rval)) {
				if (! is_dir($cdir = $makedir.'/'.$val)) {
					mkdir($cdir);
				}
				$scan2 = scandir($rval);
				foreach ($scan2 as $val2) {
					if ($val2 !== '.' && $val2 !== '..') {
						$val2 = $rval.'/'.$val2;
						if (is_dir($val2)) {
							$xdir = explode('/', $val2);
							$xdir = $cdir.'/'.$xdir[count($xdir) - 1];
							if (! is_dir($xdir)) {
								mkdir($xdir);
							}
							compile($val2, $xdir);
						} else {
							$ln = explode('/', $val2);
							$x = $cdir.'/'.$ln[count($ln) - 1];
							print 'Creating file '. $x . '...';
							$status = @copy($val2, $x);
							print " OK\n";
						}
					}
				}
			} else {
				$q = explode('/', $rval);
				$q = $makedir.'/'.$q[count($q) - 1];
				print 'Creating file '. $q . '...';
				copy($rval, $q);
				print " OK\n";
			}
		}
	}
}

function commit($dir, $licenseFile)
{
	$scan = scandir($dir);
	unset($scan[0], $scan[1]);
	$time = sha1(time());
	foreach ($scan as $val) {
		$rval = realpath($dir.'/'.$val);
		if (is_dir($rval)) {
			print 'Creating '.$rval.'/LICENSE...' . PHP_EOL;
			file_put_contents($rval.'/LICENSE', $licenseFile);
			$repoUrl = 'https://github.com/MyIceTea/'.strtolower($val);
			print shell_exec(
				'cd '.$rval.' ; '.
				'git init ; '.
				'git remote add origin '.$repoUrl.' ; '.
				'git pull origin master ; '.
				'git add . -v; '.
				'git commit -am "Update '.sha1(time()).' '.date('Y-m-d H:i:s').'";'.
				'git commit -m "Flag update '.sha1(time()).' '.date('Y-m-d H:i:s').'" --allow-empty;'.
				'git push -u origin master'
			);
		}
	}
}