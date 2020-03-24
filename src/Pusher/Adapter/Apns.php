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

    protected $socket;

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


        $payload = $message->getPayload();

        $payload['aps'] = [];

        if (!empty($message->getTitle())) {
            $payload['aps']['alert']['title'] = $message->getTitle();
            $payload['aps']['alert']['body'] = $message->getText();
        } else {
            $payload['aps']['alert'] = $message->getText();
        }

        $payload = json_encode($payload);



        switch ($message->getPriority()) {
            case MessageInterface::PRIORITY_NORMAL:
                $priority = 5;
                break;
            case MessageInterface::PRIORITY_HIGH:
            default:
                $priority = 10;
        }

        $idx = 0;

        foreach ($devices as $device) {
            /** @var Device $device */

            $this->connect();

            $token = pack('H*', str_replace(' ', '', $device->getToken()));

            $inner = chr(1).pack('n', strlen($token)).$token
                .chr(2).pack('n', strlen($payload)).$payload
                .chr(3).pack('n', 4).pack('N', $idx)
                .chr(4).pack('n', 4).pack('N', time() + $message->getTTL())
                .chr(5).pack('n', 1).chr($priority);

            $notification = chr(2).pack('N', strlen($inner)).$inner;

            fwrite($this->socket, $notification, strlen($notification));

//            $errorResponse = @fread($this->socket, 6);
//            if (!empty($errorResponse)) {
//                throw new AdapterException('error response:'.json_encode($errorResponse));
//            }

            $idx++;

            fclose($this->socket);
        }


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

    protected function connect()
    {
        $gateway = $this->environment == AdapterInterface::ENVIRONMENT_PRODUCTION ? self::PUSH_PROD : self::PUSH_DEV;

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->serverKey);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passPhrase);

        $this->socket = @stream_socket_client($gateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx);

        if ($errstr || $err) {
            throw new AdapterException($errstr, AdapterException::CAN_NOT_CONNECT);
        }

        if (!$this->socket) {
            throw new AdapterException('can not connect', AdapterException::CAN_NOT_CONNECT);
        }

//        stream_set_timeout($this->socket, 2);
//        stream_set_write_buffer($this->socket, 0);
//        stream_set_blocking($this->socket, 0);
    }
}
