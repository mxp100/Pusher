<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 3/17/17
 * Time: 2:47 PM
 */

namespace Pusher\Adapter;


use Pusher\Collection\DeviceCollection;
use Pusher\Exception\AdapterException;
use Pusher\Model\Device;
use Pusher\Model\MessageInterface;

class Apns implements AdapterInterface
{

    const PUSH_DEV = 'ssl://gateway.sandbox.push.apple.com:2195';
    const PUSH_PROD = 'ssl://gateway.push.apple.com:2195';

    const FEEDBACK_DEV = 'ssl://feedback.sandbox.push.apple.com:2196';
    const FEEDBACK_PROD = 'ssl://feedback.push.apple.com:2196';

    protected $serverKey;
    protected $passPhrase;
    protected $environment;

    /**
     * APNS adapter constructor.
     *
     * @param  string  $serverKey  Path to SSL certificate
     * @param  int  $environment  Production/development environment
     * @param  string  $passPhrase  Pass-phrase for SSL certificate
     */
    public function __construct(
        string $serverKey,
        int $environment = AdapterInterface::ENVIRONMENT_PRODUCTION,
        $passPhrase = ''
    ) {
        $this->serverKey = $serverKey;
        $this->passPhrase = $passPhrase;
        $this->environment = $environment;
    }

    /**
     * @inheritDoc
     */
    public function push(DeviceCollection $devices, MessageInterface $message): void
    {
        $gateway = $this->environment == AdapterInterface::ENVIRONMENT_PRODUCTION ? self::PUSH_PROD : self::PUSH_DEV;

        $payload = $message->getPayload();

        $payload['aps'] = [];

        if (! empty($message->getTitle())) {
            $payload['aps']['alert']['title'] = $message->getTitle();
            $payload['aps']['alert']['body'] = $message->getText();
        } else {
            $payload['aps']['alert'] = $message->getText();
        }

        $payload = json_encode($payload);

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->serverKey);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passPhrase);

        $fp = stream_socket_client($gateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!empty($errstr)) {
            throw new AdapterException($errstr, AdapterException::CAN_NOT_CONNECT);
        }

        if (!$fp) {
            throw new AdapterException('can not connect', AdapterException::CAN_NOT_CONNECT);
        }

        $idx = 1;
        foreach ($devices as $device) {
            /** @var Device $device */

            $inner = chr(1).pack('n', 32).pack('H*', str_replace(' ', '', $device->getToken()))

                .chr(2).pack('n', strlen($payload)).$payload

                .chr(3).pack('n', 4).pack('N', $idx)

                .chr(4).pack('n', 4).pack('N', time() + $message->getTTL())

                .chr(5).pack('n', 1).chr($message->getPriority());

            $notification = chr(2).pack('N', strlen($inner)).$inner;

            fwrite($fp, $notification, strlen($notification));
            $idx++;
        }

        fclose($fp);
    }

    public function getFeedback(): array
    {
        $gateway = $this->environment == AdapterInterface::ENVIRONMENT_PRODUCTION ? self::FEEDBACK_PROD : self::FEEDBACK_DEV;

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->serverKey);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passPhrase);

        $fp = stream_socket_client($gateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!empty($errstr)) {
            throw new AdapterException($errstr, AdapterException::CAN_NOT_CONNECT);
        }

        if (!$fp) {
            throw new AdapterException('can not connect', AdapterException::CAN_NOT_CONNECT);
        }

        $tokens = array();

        while (!feof($fp)) {
            $data = fread($fp, 38);
            if (strlen($data)) {
                $tokens[] = unpack("N1timestamp/n1length/H*devtoken", $data);
            }
        }
        fclose($fp);

        return $tokens;
    }
}