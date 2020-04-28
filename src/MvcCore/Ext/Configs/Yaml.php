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

namespace MvcCore\Ext\Configs;

/**
 * Responsibility - reading/writing config file(s), 
 *					detecting environment in system config.
 * - Config file(s) reading:
 *   - Reading any `config.yaml` file by rel. path with optional env. suffix.
 *   - Parsing and typing YAML data into `stdClass|array` by key types.
 * - Config file(s) writing:
 *   - Dumping `stdClass`es and `array`s into YAML syntax string without any
 *     other records for different environment (not like core config class).
 *   - Storing serialized config data in single process.
 * - Environment management and detection by:
 *   - comparing server and client IP, by value or regular expression.
 *   - comparing server hostname or IP, by value or regular expression.
 *   - checking system environment variable existence, value or by regular exp.
 */
class Yaml extends \ArrayObject implements \MvcCore\IConfig
{
	/**
	 * MvcCore - version:
	 * Comparison by PHP function `version_compare();`.
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0-alpha';

	use \MvcCore\Config\PropsGettersSetters;
	use \MvcCore\Config\ReadWrite;
	use \MvcCore\Config\MagicMethods;
	use \MvcCore\Ext\Configs\Yamls\YamlProps;
	use \MvcCore\Ext\Configs\Yamls\YamlRead;
	use \MvcCore\Ext\Configs\Yamls\YamlDump;
}
