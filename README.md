# TimeFormatter Symfony Bundle 

[![Build Status](http://jenkins.hexmedia.pl/buildStatus/icon?job=HexmediaTimeFormatter)](http://jenkins.hexmedia.pl/view/All/job/HexmediaTimeFormatter/)
[![Build Status](https://travis-ci.org/Hexmedia/TimeFormatter-Bundle.png?branch=master)](https://travis-ci.org/Hexmedia/TimeFormatter-Bundle)
[![Latest Stable Version](https://poser.pugx.org/hexmedia/time-formatter-bundle/v/stable.png)](https://packagist.org/packages/hexmedia/time-formatter-bundle)
[![Latest Unstable Version](https://poser.pugx.org/hexmedia/time-formatter-bundle/v/unstable.png)](https://packagist.org/packages/hexmedia/time-formatter-bundle)


This is a Bundle for Symfony2 Framework where you can easli convert a datetime/timestamp to distance of time in words.

By example

	{{ var.data|time_formatter }}

Outputs

	day ago

# Installation for Symfony2

1) Update your composer.json

```
{
	"require": {
		"Hexmedia/TimeFormatterBundle": "dev-master"
	}
}
```

or use composer's require command:

	composer require Hexmedia/TimeFormatterBundle:dev-master

2) Update AppKernel
```
	public function registerBundles() {
		$bundles = array(
			...
			new Hexmedia\TimeFormatterBundle\HexmediaTimeFormatterBundle(),
			...
			);
		return $bundles;
	}
```

# Usage

To display distance of time in words between a date and current date:

	{{ var.data|time_formatter }}


To display distance of time between two custom dates you should use

	{{ var.data|time_formatter(message.updated) }}


