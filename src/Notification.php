<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

use UnderflowException;

class Notification extends Base
{
    /**
     * @return array
     * @throws UnderflowException
     */
    public function __invoke()
    {
        return parent::__invoke();
    }

    public function setNotification($title, $message, $data = null)
    {
        $this->validateCurrent($title, $message);

        $payload = [
            'notification' => [
                'title' => $title,
                'body'  => $message,
            ],
            'android' => [
                'notification' => [
                    'sound'              => 'default',
                    'notification_count' => 1,
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                        'badge' => 1,
                    ],
                ],
            ],
        ];

        if (is_array($data) && count($data) > 0) {
            $payload['data'] = $data;
        }

        $this->setPayload($payload);
    }
}
