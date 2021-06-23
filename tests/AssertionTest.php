<?php

namespace Mpyw\PHPUnitPatchSerializableComparison\Tests;

use PHPUnit\Framework\TestCase;

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
        static::callAssertSameReceivingClosure(function () {});
    }
}
