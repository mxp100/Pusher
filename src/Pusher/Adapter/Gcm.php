<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/23/17
 * Time: 2:21 PM
 */

namespace Pusher\Adapter;


use Pusher\Collection\DeviceCollection;
use Pusher\Exception\AdapterException;
use Pusher\Model\MessageInterface;

class Gcm implements AdapterInterface
{
    const API_URL = 'https://gcm-http.googleapis.com/gcm/send';

    protected $serverKey;
    protected $environment;
    protected $invalidTokens = [];

    /**
     * GCM adapter constructor.
     *
     * @param  string  $serverKey  Path to SSL certificate
     * @param  int  $environment  Production/development environment
     */
    public function __construct(string $serverKey, int $environment = AdapterInterface::ENVIRONMENT_DEVELOPMENT)
    {
        $this->serverKey = $serverKey;
        $this->environment = $environment;
    }

    /**
     * @inheritDoc
     */
    public function push(DeviceCollection $devices, MessageInterface $message): void
    {
        $tokens = $devices->getTokens();

        $data = [
            'notification'     => [
                'title' => $message->getTitle(),
                'body'  => $message->getText(),
            ],
            'registration_ids' => $tokens,
            'time_to_live'     => $message->getTTL(),
        ];

        switch ($message->getPriority()) {
            case MessageInterface::PRIORITY_HIGH:
                $data['priority'] = 'high';
                break;
            case MessageInterface::PRIORITY_NORMAL:
                $data['priority'] = 'normal';
                break;
        }

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: key='.$this->serverKey,
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
        ]);

        if (!$response = curl_exec($ch)) {
            throw new AdapterException('invalid response', AdapterException::INVALID_RESPONSE);
        }

        if (!$response = json_decode($response, true)) {
            throw new AdapterException('invalid response json', AdapterException::INVALID_RESPONSE_JSON);
        }

        foreach ($response['results'] as $k => $result) {
            if (!empty($result['error'])) {
                $this->invalidTokens[] = $tokens[$k];
            }
        }
    }

    public function getFeedback(): array
    {
        $result = $this->invalidTokens;
        $this->invalidTokens = [];
        return $result;
    }
}