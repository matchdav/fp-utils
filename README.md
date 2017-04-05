# fp-utils

These docs are a WIP.  Meanwhile, you can read the unit tests to get a better sense of what these utilities can do for you.

## use cases

### safe property access

```php
$bar = Access::get($data,'foo.bar');
$hazBar = Access::has($data,'foo.bar');
$methodCallResult = Access::result($data,'someMethodOrProperty','defaultValue');
```
### safe property mutation
```php
Access::set($data,'foo.bar','baz');
```

### cheap configurables

```php
$model = new Configurable(['name'=>'dave']);
$model->set('age',45);
assert($model->get('name') === 'dave');
assert($model->keys()==['name','age']);
assert($model->values()==['dave',45]);
```
configurables are also iterable:
```php
foreach($model as $key => $value) {
  //do things with model props.
}
```

### function composition

make pipelines of functions, where the result of one method is passed to the next
```php
$double = function ($a) { return $a*2; };
$square = function ($a) { return pow($a,2); };
$numbers = [1,2,3];
$doubleThenSquare = Functional::flow($double,$square);
Collections::map($numbers,$doubleThenSquare);
// [4,16,64]
$squareThenDouble = Functional::compose($double,$square);
Collections::map($numbers,$squareThenDouble);
// [2,8,18]
```
### partial functions
```php
$concat = function ($a,$b) { return $a . $b; };
$appendCat = Functional::partial($concat,'cat');
$prependCat = Functional::partialRight($concat,'cat');
assert($appendCat('egory') === 'category');
assert($prependCat('tom') === 'tomcat');
```
### currying
```php
$concat = function ($a, $b, $c) {
	return implode(', ',[$a,$b,$c]);
};
$concatCat = Functional::curry($concat,'cat');
$prependCat = Functional::curryRight($concat,'cat');
assert(is_callable($concatCat('dog'));
assert($concatCat('dog')('mouse') === 'mouse, dog, cat');
assert($prependCat('dog')('mouse') === 'cat, dog, mouse');
```
