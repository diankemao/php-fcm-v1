<?php

/**
 * This file is part of DianKeMao
 *
 * @author   Allen.Liang
 * @contact  diankemao@163.com
 */

namespace phpFCMv1;

use DateTime;
use Firebase\JWT\JWT;
use GuzzleHttp;
use InvalidArgumentException;
use RuntimeException;

class Credentials
{
    public const SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    public const TOKEN_URL = 'https://www.googleapis.com/oauth2/v4/token';

    public const EXPIRE = 3600;

    public const ALG = 'RS256';

    public const CONTENT_TYPE = 'form_params';

    public const GRANT_TYPE = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

    public const METHOD = 'POST';

    public const HTTP_ERRORS_OPTION = 'http_errors';

    private $keyFilePath;

    private $DATA_TYPE;

    /**
     * Credentials constructor. Checks whether given path is a valid file.
     * @param string $keyFile
     * @throws InvalidArgumentException when file is not found
     */
    public function __construct($keyFile = 'service_account.json')
    {
        if (is_file($keyFile)) {
            $this->setKeyFilePath($keyFile);
        } else {
            throw new InvalidArgumentException('Key file could not be found', 1);
        }
    }

    /**
     * @return string Access token for a project
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken()
    {
        $requestBody = [
            'grant_type' => self::GRANT_TYPE,
            'assertion'  => $this->getTokenPayload(),
        ];

        $result = $this->getToken($requestBody);

        if (isset($result['error'])) {
            throw new RuntimeException($result['error_description']);
        }
        if (empty($result['access_token'])) {
            throw new RuntimeException('Access Token Empty');
        }
        return $result['access_token'];
    }

    /**
     * @return mixed
     */
    public function getProjectID()
    {
        $keyBody = json_decode(
            file_get_contents($this->getKeyFilePath()),
            true
        );
        if (empty($keyBody)) {
            throw new RuntimeException('Project Id Is Empty');
        }
        return $keyBody['project_id'];
    }

    /**
     * @return mixed
     */
    public function getKeyFilePath()
    {
        return $this->keyFilePath;
    }

    /**
     * @param mixed $keyFilePath
     */
    public function setKeyFilePath($keyFilePath)
    {
        $this->keyFilePath = $keyFilePath;
    }

    /**
     * @return string Signed payload (with private key using algorithm)
     *
     * @return string
     */
    private function getTokenPayload()
    {
        $keyBody = json_decode(
            file_get_contents($this->getKeyFilePath()),
            true
        );
        $now = (new DateTime())->format('U');
        $iat = intval($now);
        if (empty($keyBody)) {
            throw new RuntimeException('Service Account Empty');
        }

        $payload = [
            'iss'   => $keyBody['client_email'],
            'scope' => self::SCOPE,
            'aud'   => self::TOKEN_URL,
            'iat'   => $iat,
            'exp'   => $iat + self::EXPIRE,
            'sub'   => null,
        ];
        return JWT::encode($payload, $keyBody['private_key'], self::ALG);
    }

    /**
     * @param $requestBody array    Payload with assertion data (which is signed)
     * @return array Associative array of cURL result
     * @throws GuzzleHttp\Exception\GuzzleException
     *                                              This exception is intentional
     */
    private function getToken($requestBody)
    {
        $client   = new GuzzleHttp\Client();
        $response = $client->request(
            self::METHOD,
            self::TOKEN_URL,
            [self::CONTENT_TYPE => $requestBody, self::HTTP_ERRORS_OPTION => false]
        );

        return json_decode($response->getBody(), true);
    }
}
