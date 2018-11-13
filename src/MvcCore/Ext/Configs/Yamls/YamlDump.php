<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Configs\Yamls;

use Symfony\Component\Yaml\Yaml;

/**
 *
 */
trait YamlDump
{
	protected static $dumpingFlags = 0;

	public static function SetDumpingFlags ($dumpingFlags) {
		self::$dumpingFlags = $dumpingFlags;
	}

	public static function GetDumpingFlags () {
		return self::$dumpingFlags;
	}

	public function Dump () {
		$dataClone = unserialize(serialize($this->data));
		$maxLevel = 0;
		$dataClone = $this->dumpYamlObjectTypes($dataClone, $maxLevel);
		$result = Yaml::dump($dataClone, $maxLevel, 4, self::$dumpingFlags);
		if (!$result) $result = FALSE;
		return $result;
	}

	protected function dumpYamlObjectTypes (& $data, & $maxLevel, $level = 0) {
		if (is_object($data)) 
			$data = (array) $data;
		if (is_array($data) || is_object($data)) {
			$level += 1;
			if ($level > $maxLevel) 
				$maxLevel = $level;
			foreach ($data as & $value) {
				if (is_object($value)) 
					$value = (array) $value;
				if (is_array($value)) 
					$value = $this->dumpYamlObjectTypes($value, $maxLevel, $level);
			}
		}
		return $data;
	}
}
