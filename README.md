# PHPUnit Patch Serializable Comparison [![Build Status](https://travis-ci.com/mpyw/phpunit-patch-serializable-comparison.svg?branch=master)](https://travis-ci.com/mpyw/phpunit-patch-serializable-comparison) 

Fixes `assertSame()`/`assertEquals()` serialization errors running in separate processes.

## Requirements

- php: `>=5.3.3`
- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit): `>=4.8.0`
- [sebastianbergmann/comparator](https://github.com/sebastianbergmann/comparator): `^1.0 || ^2.0 || ^3.0 || ^4.0`

## Installing

```bash
composer require --dev mpyw/phpunit-patch-serializable-comparison
```

## Example

```php
class AssertionTest extends TestCase
{
    protected function callAssertSameReceivingClosure(\Closure $closure)
    {
        static::assertSame('aaa', 'bbb');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAssertionIncludingUnserializableTrace()
    {
        static::callAssertSameInClosure(function () {});
    }
}
```

### Before Patching

```
PHPUnit\Framework\Exception: PHP Fatal error:  Uncaught Exception: Serialization of 'Closure' is not allowed in Standard input code:XX
Stack trace:
#0 Standard input code(XX): serialize(Array)
#1 Standard input code(XX): __phpunit_run_isolated_test()
#2 {main}
  thrown in Standard input code on line XX
```

### After Patching

```diff
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'aaa'
+'bbb'
```
