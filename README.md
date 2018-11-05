# MvcCore Extension - Config - YAML

[![Latest Stable Version](https://img.shields.io/badge/Stable-v4.3.1-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-config-yaml/releases)
[![License](https://img.shields.io/badge/Licence-BSD-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.3-brightgreen.svg?style=plastic)

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
