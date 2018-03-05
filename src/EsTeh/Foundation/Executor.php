<?php

namespace EsTeh\Foundation;

class Executor
{
	public function execute($callable)
	{
		$callable();
	}
}