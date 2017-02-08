[<img src="https://cdn.rawgit.com/lutsen/lagan/master/lagan-logo.svg" width="100" alt="Lagan">](https://github.com/lutsen/lagan)

Lagan Objectlink Property Controller
====================================

Controller for the Lagan Objectlink property.  
Links to any object in the database. The object is specified by it's type and id. If id == false, all objects of this type are linked. In the Lagan model an array needs to be defined with the object types that can be linked to. In this array the type of object models that can be used are the keys, and the type of link are the values. Linktype can be "single" to only link to singel objects, "group" to only link to the entire group of objects, and "all" to link to both.

Example:

```php
[
	'name' => 'object',
	'description' => 'Content object',
	'type' => '\Lagan\Property\Objectlink',
	'input' => 'objectlink',
	'models' => [
		'Content' => 'single',
		'Genre' => 'group',
		'Employee' => 'group',
		'Publication' => 'all'
	]
]
```

To be used with [Lagan](https://github.com/lutsen/lagan). Lagan lets you create flexible content objects with a simple class, and manage them with a web interface.

Lagan is a project of [Lútsen Stellingwerff](http://lutsen.land/) from [HoverKraft](http://www.hoverkraft.nl/).