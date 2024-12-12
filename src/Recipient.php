<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

class Recipient extends Base
{
    private static $TOKEN = 1;

    private static $TOPIC = 2;

    private static $CONDITION = 3;

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        // TODO: Constructor로 한 번에 정의할 수 있도록 하자
    }

    public function setSingleRecipient($token)
    {
        $this->validateCurrent($token);
        $this->setPayload(
            ['token' => $token]
        );
    }

    public function setTopicRecipient($topic)
    {
        $this->validateCurrent($topic);
        $this->setPayload(
            ['topic' => $topic]
        );
    }

    public function setConditionalRecipient($condition)
    {
        $this->validateCurrent($condition);
        $this->setPayload(
            ['condition' => $condition]
        );
    }
}
