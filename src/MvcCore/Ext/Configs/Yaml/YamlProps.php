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

namespace MvcCore\Ext\Configs\Yaml;

/**
 * @mixin \MvcCore\Ext\Configs\Yaml
 */
trait YamlProps {

	/**
	 * System config relative path from app root.
	 * This value could be changed to any value at the very application start.
	 * @var string
	 */
	protected static $systemConfigPath = '/%appPath%/config.yaml';
}
