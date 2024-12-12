<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1\Config;

interface CommonConfig
{
    public function __invoke();

    /**
     * @param $key
     * @return mixed
     */
    public function setCollapseKey($key);

    /**
     * @param $priority
     * @return mixed
     */
    public function setPriority($priority);

    /**
     * @param $time
     * @return mixed
     */
    public function setTimeToLive($time);

    /**
     * @return mixed
     */
    public function getPayload();
}
