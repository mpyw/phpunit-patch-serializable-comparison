<?php

namespace PHPUnit\Framework;

if (class_exists('\PHPUnit_Framework_TestCase') && !class_exists('\PHPUnit\Framework\TestCase')) {
    class TestCase extends \PHPUnit_Framework_TestCase
    {
    }
}
