# TimeAgo Symfony Bundle

This is a Bundle for Symfony2 Framework where you can easli convert a datetime/timestamp to distance of time in words.

By example

	{{ var.data|time_ago }}

Outputs

	day ago

# Installation for Symfony2

1) Update your composer.json

```
{
	"require": {
		"Hexmedia/TimeAgoBundle": "dev-master"
	}
}
```

or use composer's require command:

	composer require Hexmedia/TimeAgoBundle:dev-master

2) Update AppKernel
```
	public function registerBundles() {
		$bundles = array(
			...
			new Hexmedia\TimeAgoBundle\HexmediaTimeAgoBundle(),
			...
			);
		return $bundles;
	}
```

# Usage

To display distance of time in words between a date and current date:

	{{ var.data|time_ago }}


To display distance of time between two custom dates you should use

	{{ var.data|time_ago(null, message.updated) }}


