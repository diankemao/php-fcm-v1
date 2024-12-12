<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1\tests;

use phpFCMv1\Config;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConfigTest extends TestCase
{
    public function testSetCollapseKey()
    {
        // $this -> markTestSkipped("Skipping passed test");
        $fcmTest = new FCMTest();
        $config  = new Config();

        $config->setCollapseKey('test');
        $result = $fcmTest->fireWithConfig($config);

        $this->assertTrue($result);
    }

    public function testSetTimeToLive()
    {
        // $this -> markTestSkipped("Implemented");
        $fcmTest = new FCMTest();
        $config  = new Config();

        $config->setTimeToLive(10);
        $result = $fcmTest->fireWithConfig($config);

        $this->assertTrue($result);
    }

    public function testSetPriority()
    {
        // $this -> markTestSkipped("Not Implemented");
        $fcmTest = new FCMTest();
        $config  = new Config();

        $config->setPriority(Config::PRIORITY_HIGH);
        $result = $fcmTest->fireWithConfig($config);

        $this->assertTrue($result);
    }
}
