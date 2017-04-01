# fp-utils

## use cases

### safe property access

```php
$bar = Access::get($data,'foo.bar');
```
### safe property mutation
```php
Access::set($data,'foo.bar','baz');
```

### configurables

```php
$model = new Configurable(['name'=>'dave']);
$model->set('age',45);
assert($model->keys()==['name','age']);
assert($model->values()==['dave',45]);
```
configurables are also iterators so this works:

```php
foreach($model as $key => $value) {
  //do things with model props.
}
```

to be continued
