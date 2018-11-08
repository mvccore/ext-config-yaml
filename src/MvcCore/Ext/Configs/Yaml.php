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
 * 
 */
class Yaml extends \ArrayObject implements \MvcCore\IConfig
{
	use \MvcCore\Config\Environment;
	use \MvcCore\Config\PropsGettersSetters;
	use \MvcCore\Config\ReadingWriting;
	use \MvcCore\Config\MagicMethods;
	use \MvcCore\Ext\Configs\Yamls\YamlProps;
	use \MvcCore\Ext\Configs\Yamls\YamlRead;
	use \MvcCore\Ext\Configs\Yamls\YamlDump;
}
