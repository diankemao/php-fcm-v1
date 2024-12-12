<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1\Config;

use DateInterval;
use DateTime;
use Exception;

class APNsConfig implements CommonConfig
{
    public const PRIORITY_HIGH = '10';

    public const PRIORITY_NORMAL = '5';

    private $payload;

    public function __construct()
    {
        $this->payload = [];
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
        $payload       = array_merge($this->payload, ['apns-collapse-id' => $key]);
        $this->payload = $payload;

        return null;
    }

    /**
     * @param $priority
     * @return mixed
     */
    public function setPriority($priority)
    {
        $payload       = array_merge($this->payload, ['apns-priority' => $priority]);
        $this->payload = $payload;

        return null;
    }

    /**
     * @param $time : Time for notification to live in seconds
     * @return mixed : Expiration option using UNIX epoch date
     * @throws Exception
     */
    public function setTimeToLive($time)
    {
        $expiration = DateTime::createFromFormat('U', $this->roundUpMilliseconds());
        $expiration->add(new DateInterval('PT' . $time . 'S'));
        $expValue = $expiration->format('U');

        $payload       = array_merge($this->payload, ['apns-expiration' => $expValue]);
        $this->payload = $payload;

        return null;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        if (! sizeof($this->payload)) {
            // To prevent erorr on array_merge. Returns empty array
            return $this->payload;
        }
        // 'apns' should have 'header' & 'payload' field
        return ['apns' => ['headers' => $this->payload]];
    }

    /**
     * Path for PHP@7.2. Refer to the issue.
     * https://github.com/lkaybob/php-fcm-v1/issues/3.
     * @return string
     */
    private function roundUpMilliseconds()
    {
        $converted = new DateTime('now');

        if ($converted->format('u') != 0 && strpos(PHP_VERSION, '7.1') !== 0) {
            $converted = $converted->add(new DateInterval('PT1S'));
        }

        return $converted->format('U');
    }
}
