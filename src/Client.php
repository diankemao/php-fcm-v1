<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

use GuzzleHttp;
use InvalidArgumentException;
use phpFCMv1\Config\CommonConfig;
use RuntimeException;
use UnderflowException;

class Client
{
    public const SEND_URL = 'https://fcm.googleapis.com/v1/projects/$0/messages:send';

    public const CONTENT_TYPE = 'json';

    public const HTTP_ERRORS_OPTION = 'http_errors';

    private $credentials;

    private $payload;

    private $URL;

    public function __construct($keyFile)
    {
        $this->credentials = new Credentials($keyFile);
        $this->setProjectID();
        $this->payload = ['message' => null];
    }

    /**
     * @param Recipient $recipient : Recipient token or topic for the notificaation
     * @param null|Notification $notification : Notification with title & body to send.
     *                                        Not required, if only downstream data payload is needed
     * @param null|Data $data : (Optional) Downstream data payload to send
     * @param null|CommonConfig $config : (Optional) CommonConfig instance to define optional characteristics
     *                                  of notification
     */
    public function build(Recipient $recipient, ?Notification $notification = null, ?Data $data = null, ?CommonConfig $config = null)
    {
        $result     = $recipient();
        $isPlayload = false;

        if (! is_null($notification)) {
            $result     = array_merge($result, $notification());
            $isPlayload = true;
        }
        if (! is_null($data)) {
            $result     = array_merge($result, $data());
            $isPlayload = true;
        }

        if (! is_null($config)) {
            $result = array_merge($result, $config());
        }

        if (! $isPlayload) {
            throw new UnderflowException('Neither notification or data object has not been set');
        }
        $this->setPayload($result);
    }

    /**
     * Send Fires built message.
     *
     * @return array
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function fire()
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->credentials->getAccessToken(),
            ],
        ];
        $body = [
            self::CONTENT_TYPE       => $this->getPayload(),
            self::HTTP_ERRORS_OPTION => false,
        ];
        // Class name conflict occurs, when used as "Client"
        $client   = new GuzzleHttp\Client($options);
        $response = $client->request('POST', $this->getURL(), $body);
        $code     = $response->getStatusCode();
        $result   = (array) json_decode($response->getBody(), true);

        if ($code == 200) {
            return $result;
        }
        throw new RuntimeException('fcmError: ' . $result['error']['message'] ?? '', $code);
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload['message'] = $payload;
    }

    public function setValidateOnly($option)
    {
        if (is_bool($option)) {
            $this->payload['validate_only'] = $option;
        } else {
            throw new InvalidArgumentException('validate_only option only allows boolean');
        }
    }

    public function getURL()
    {
        return $this->URL;
    }

    private function setProjectID()
    {
        $projectId = $this->credentials->getProjectID();
        $pattern   = '/\$0/';
        $result    = preg_replace($pattern, $projectId, self::SEND_URL);
        $this->setURL($result);
    }

    private function setURL($result)
    {
        $this->URL = $result;
    }
}
