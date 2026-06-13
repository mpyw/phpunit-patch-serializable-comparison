# PHPUnit Patch Serializable Comparison [![Status](https://github.com/mpyw/phpunit-patch-serializable-comparison/actions/workflows/test.yml/badge.svg?branch=master)](https://github.com/mpyw/phpunit-patch-serializable-comparison/actions)

Fixes `assertSame()`/`assertEquals()` serialization errors running in separate processes.

> [!IMPORTANT]
> **`sebastian/comparator` `8.2.0` fixes this upstream**, so this patch is only needed below that version.
> See [sebastianbergmann/comparator#158](https://github.com/sebastianbergmann/comparator/issues/158): as of `8.2.0`, `ComparisonFailure` implements `__serialize()`/`__unserialize()` and can be serialized across process-isolation boundaries even when its stack trace references non-serializable objects (e.g. a `PDO` passed as a method argument) — exactly the case this package works around.
> This package is therefore constrained to `sebastian/comparator` `<8.2`. On `comparator` `>=8.2` you do **not** need it (and shouldn't install it: it would override the upstream-fixed class).

## Requirements

- php: `>=5.3.3`
- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit): `>=4.8.0`
- [sebastianbergmann/comparator](https://github.com/sebastianbergmann/comparator): `^1.0 || ^2.0 || ^3.0 || ^4.0 || ^5.0 || ^6.0 || ^7.0 || >=8.0 <8.2`

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

    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
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
