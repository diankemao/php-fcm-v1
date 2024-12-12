<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

use BadMethodCallException;
use InvalidArgumentException;
use UnderflowException;

abstract class Base
{
    protected $payload;

    /**
     * @return array
     * @throws UnderflowException
     */
    public function __invoke()
    {
        return $this->getPayload();
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param array ...$arg
     */
    protected function validateCurrent(...$arg)
    {
        $this->validateArg($arg);
        $this->checkPayloadNull();
    }

    protected function checkPayloadNull()
    {
        if (isset($this->payload)) {
            throw new BadMethodCallException('Target has already been set', 1);
        }
    }

    /**
     * @param array ...$arg
     * @throws InvalidArgumentException when item is not defined (null)
     */
    protected function validateArg(array $arg)
    {
        foreach ($arg as $index => $item) {
            if (is_null($item)) {
                throw new InvalidArgumentException('Argument is not defined: ' . $index);
            }
        }
    }
}
