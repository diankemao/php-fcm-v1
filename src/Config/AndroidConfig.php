<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1\Config;

class AndroidConfig implements CommonConfig
{
    public const PRIORITY_HIGH = 'HIGH';

    public const PRIORITY_NORMAL = 'NORMAL';

    private $payload;

    public function __construct()
    {
        $this->payload = [];
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        return $this->getPayload();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setCollapseKey($key)
    {
        $payload       = array_merge($this->payload, ['collapse_key' => $key]);
        $this->payload = $payload;

        return null;
    }

    /**
     * @param $priority
     * @return mixed
     */
    public function setPriority($priority)
    {
        $payload       = array_merge($this->payload, ['priority' => $priority]);
        $this->payload = $payload;

        return null;
    }

    /**
     * @param $image : string
     * @return mixed
     */
    public function setImage($image)
    {
        $payload       = array_merge($this->payload, ['notification' => ['image' => $image]]);
        $this->payload = $payload;
        return null;
    }

    /**
     * @param $time
     * @return mixed
     */
    public function setTimeToLive($time)
    {
        $payload       = array_merge($this->payload, ['ttl' => $time . 's']);
        $this->payload = $payload;

        return null;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        if (! sizeof($this->payload)) {
            return null;
        }
        return ['android' => $this->payload];
    }
}
