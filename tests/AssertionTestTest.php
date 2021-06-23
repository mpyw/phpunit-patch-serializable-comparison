<?php

namespace Mpyw\PHPUnitPatchSerializableComparison\Tests;

use PHPUnit\Framework\TestCase;

class AssertionTestTest extends TestCase
{
    public function testAssertionTest()
    {
        $php = \popen(\vsprintf(
            '%s %s %s',
            \array_map('escapeshellarg', array(
                \defined('PHP_BINARY') ? \PHP_BINARY : 'php',
                __DIR__ . '/../vendor/bin/phpunit',
                __DIR__ . '/AssertionTest.php',
            ))
        ), 'rb');

        $method = \method_exists($this, 'assertMatchesRegularExpression')
            ? 'assertMatchesRegularExpression'
            : 'assertRegExp';

        $pattern = <<<EOD
Failed asserting that two strings are identical[.]
[-][-][-] Expected
[+][+][+] Actual
@@ @@
[-]'?aaa'?
[+]'?bbb'?
EOD;

        $this->$method("/$pattern/", \stream_get_contents($php));
    }
}
