# MvcCore - Extension - Config - Yaml

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.0.0-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-config-yaml/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

Yaml configuration files syntax.

## Installation
```shell
composer require mvccore/ext-config-yaml
```

## Usage
Add this to `Bootstrap.php` or to very application beginning:
```php
\MvcCore\Ext\Auth::GetInstance()->SetConfigClass('MvcCore\Ext\Config\Yaml');
```
