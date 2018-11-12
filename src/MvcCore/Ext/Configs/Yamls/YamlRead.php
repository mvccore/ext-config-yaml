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
trait YamlRead
{
	protected static $readingFlags = 0;

	public static function SetReadingFlags ($readingFlags) {
		self::$readingFlags = $readingFlags;
	}

	public static function GetReadingFlags () {
		return self::$readingFlags;
	}

	/**
	 * Load config file and return `TRUE` for success or `FALSE` in failure.
	 * - Second environment value setup:
	 *   - Only if `$this->system` property is defined as `TRUE`.
	 *   - By defined IPs or computer names in `environments` section.
	 * - Load also environment specific config files and merge with already loaded data.
	 * - Return all `raw string` values as `array`, `float`, `int` or `boolean` types.
	 * - Retype whole values level into `\stdClass`, if there are no numeric keys.
	 * @param string $fullPath
	 * @param bool $systemConfig
	 * @return bool
	 */
	public function Read ($fullPath, $systemConfig = FALSE) {
		if ($this->data) return $this->data;
		$this->fullPath = $fullPath;
		$this->system = $systemConfig;
		clearstatcache(TRUE, $fullPath);
		$this->lastChanged = filemtime($fullPath);
		$rawContent = file_get_contents($fullPath);
		if ($rawContent === FALSE) return FALSE;
		$rawYamlData = NULL;
		try {
			$rawYamlData = Yaml::parse(str_replace("\t", '    ', $rawContent), self::$readingFlags);
		} catch (\Exception $e) {
			if ($systemConfig) {
				throw $e;
			} else {
				\MvcCore\Debug::Log($e);
			}
		}
		if (!$rawYamlData) return FALSE;
		$this->data = [];
		$environment = $this->system
			? static::environmentDetectBySystemConfig($rawYamlData)
			: static::$environment;
		$environmentConfigFullPath = mb_substr($this->fullPath, 0, -4) . $environment . '.yaml';
		if (file_exists($environmentConfigFullPath)) {
			$rawEnvironmentContent = file_get_contents($environmentConfigFullPath);
			if ($rawEnvironmentContent !== FALSE) {
				$rawEnvYamlData = NULL;
				try {
					$rawEnvYamlData = Yaml::parse(
						str_replace("\t", '    ', $rawEnvironmentContent), self::$readingFlags
					);
				} catch (\Exception $e) {
					if ($systemConfig) {
						throw $e;
					} else {
						\MvcCore\Debug::Log($e);
					}
				}
				if ($rawEnvYamlData) 
					$rawYamlData = array_replace_recursive($rawYamlData, $rawEnvYamlData);
			}
		}
		$this->data = & $rawYamlData;
		foreach ($rawYamlData as $firstLevelKey => & $firstlLevelValue)
			$this->readYamlObjectTypes($firstlLevelValue, $firstLevelKey);
		foreach ($this->objectTypes as & $objectType) 
			if ($objectType[0]) $objectType[1] = (object) $objectType[1];
		unset($this->objectTypes);
		return TRUE;
	}

	protected function readYamlObjectTypes (& $data, $levelKey) {
		if (is_array($data)) {
			$numericKeyCatched = FALSE;
			foreach ($data as $key => & $value) {
				if (is_numeric($key)) 
					$numericKeyCatched = TRUE;
				if (is_array($value)) 
					$this->readYamlObjectTypes($value, $levelKey . '.' . $key);
			}
			$this->objectTypes[$levelKey] = [$numericKeyCatched ? 0 : 1, & $data];
		}
	}
}
