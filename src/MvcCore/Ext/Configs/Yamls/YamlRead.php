<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Configs\Yamls;

use Symfony\Component\Yaml\Yaml;

trait YamlRead {

	/**
	 * Global flags configuration for YAML reading.
	 * @var int
	 */
	protected static $readingFlags = 0;

	/**
	 * Set globally MvcCore YAML Config reading flags.
	 * @param int $readingFlags
	 * @return int
	 */
	public static function SetReadingFlags ($readingFlags) {
		return self::$readingFlags = $readingFlags;
	}

	/**
	 * Get globally configured MvcCore YAML Config reading flags.
	 * @return int
	 */
	public static function GetReadingFlags () {
		return self::$readingFlags;
	}

	/**
	 * Load config file and return `TRUE` for success or `FALSE` in failure.
	 * - Second environment value setup:
	 *   - Only if `$this->system` property is defined as `TRUE`.
	 *   - By defined client IPs, server hostnames or environment variables
	 *     in `environments` section. By values or regular expressions.
	 * - Load also environment specific config files and merge with already loaded data.
	 * - Return all `raw string` values as `array`, `float`, `int` or `boolean` types.
	 * - Retype whole values level into `\stdClass`, if there are no numeric keys.
	 * @param string $fullPath
	 * @param bool $systemConfig
	 * @return bool
	 */
	public function Read () {
		/** @var $this \MvcCore\Config */
		if ($this->envData) return TRUE;
		
		$app = self::$app ?: self::$app = \MvcCore\Application::GetInstance();
		$environmentClass = $app->GetEnvironmentClass();
		$allEnvNames = array_merge([''], $environmentClass::GetAllNames());
		$fullPathLastDot = mb_strrpos($this->fullPath, '.');
		$fullPathParts = [
			mb_substr($this->fullPath, 0, $fullPathLastDot),
			'',
			mb_substr($this->fullPath, $fullPathLastDot),
		];
		foreach ($allEnvNames as $envName) {
			$commonEnv = $envName === '';
			if ($commonEnv) {
				$fullPath = $this->fullPath;
			} else {
				$fullPathParts[1] = '.' . $envName;
				$fullPath = implode('', $fullPathParts);
			}
			
			clearstatcache(TRUE, $fullPath);
			if (!file_exists($fullPath)) {
				if ($commonEnv) return FALSE;
				continue;
			}
			
			$lastChanged = filemtime($fullPath);
			if ($lastChanged > $this->lastChanged)
				$this->lastChanged = $lastChanged;
			
			$rawContent = file_get_contents($fullPath);
			if ($rawContent === FALSE) {
				if ($commonEnv) return FALSE;
				continue;
			}

			$rawYamlData = NULL;
			try {
				$rawYamlData = Yaml::parse(
					str_replace("\t", '    ', $rawContent), 
					self::$readingFlags
				);
			} catch (\Exception $e) { // backward compatibility
				\MvcCore\Debug::Exception($e);
			} catch (\Throwable $e) {
				\MvcCore\Debug::Exception($e);
			}
		
			if (!$rawYamlData) {
				if ($commonEnv) return FALSE;
				continue;
			}

			$objectTypes = [];
			foreach ($rawYamlData as $firstLevelKey => & $firstlLevelValue)
				$this->readYamlObjectTypes($objectTypes, $firstlLevelValue, $firstLevelKey);
			foreach ($objectTypes as & $objectType)
				if ($objectType[0]) $objectType[1] = (object) $objectType[1];
			unset($objectTypes);

			$this->envData[$envName] = $rawYamlData;
		}
		
		return TRUE;
	}

	/**
	 * Process all decoded YAML arrays and detect if there are all keys numeric
	 * or not. If there is no numeric key, convert that array into `\stdClass`.
	 * @param array $data
	 * @param string $levelKey
	 * @return void
	 */
	protected function readYamlObjectTypes (& $objectTypes, & $data, $levelKey) {
		if (is_array($data)) {
			$numericKeyCatched = FALSE;
			foreach ($data as $key => & $value) {
				if (is_numeric($key))
					$numericKeyCatched = TRUE;
				if (is_array($value))
					$this->readYamlObjectTypes($objectTypes, $value, $levelKey . '.' . $key);
			}
			$objectTypes[$levelKey] = [$numericKeyCatched ? 0 : 1, & $data];
		}
	}
}
