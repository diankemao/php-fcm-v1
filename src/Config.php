<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

use Exception;
use InvalidArgumentException;
use phpFCMv1\Config\AndroidConfig;
use phpFCMv1\Config\APNsConfig;
use phpFCMv1\Config\CommonConfig;

class Config implements CommonConfig
{
    public const PRIORITY_HIGH = 1;

    public const PRIORITY_NORMAL = 2;

    private $androidConfig;

    private $apnsConfig;

    public function __construct()
    {
        $this->androidConfig = new AndroidConfig();
        $this->apnsConfig    = new APNsConfig();
    }

    public function __invoke()
    {
        return $this->getPayload();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setCollapseKey($key)
    {
        $this->androidConfig->setCollapseKey($key);
        $this->apnsConfig->setCollapseKey($key);

        return null;
    }

    /**
     * @param $priority
     * @return mixed
     */
    public function setPriority($priority)
    {
        switch ($priority) {
            case self::PRIORITY_HIGH:
                $this->androidConfig->setPriority(AndroidConfig::PRIORITY_HIGH);
                $this->apnsConfig->setPriority(APNsConfig::PRIORITY_HIGH);
                break;
            case self::PRIORITY_NORMAL:
                $this->androidConfig->setPriority(AndroidConfig::PRIORITY_NORMAL);
                $this->apnsConfig->setPriority(APNsConfig::PRIORITY_NORMAL);
                break;
            default:
                throw new InvalidArgumentException('Priority option not proper');
                break;
        }

        return null;
    }

    /**
     * @param $image : string
     * @param mixed $image_url
     * @return mixed
     */
    public function setImage($image_url)
    {
        $this->androidConfig->setImage($image_url);
        return null;
    }

    /**
     * @param $time : seconds
     * @return mixed
     */
    public function setTimeToLive($time)
    {
        try {
            $this->androidConfig->setTimeToLive($time);
            $this->apnsConfig->setTimeToLive($time);
        } catch (Exception $e) {
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        $androidConfig = $this->androidConfig->getPayload();
        $apnsConfig    = $this->apnsConfig->getPayload();

        return array_merge($androidConfig, $apnsConfig);
    }
}
