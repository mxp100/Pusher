# Pusher
Compatable: PHP 7+
Example for APNS:
```php
$serverKey = '[server key]';
$deviceId = '[device token]';

$devices = new DeviceCollection([new Device($deviceId)]);
$message = new Message('This is a test message');

$adapter = new Fcm($serverKey);

$pusher = new Pusher([new Push($adapter, $devices, $message)]);
$pusher->push();
```
Example for FCM:
```php
$serverKey = '[path to certification]';
$deviceId = '[device token]';

$devices = new DeviceCollection([new Device($deviceId)]);
$message = new Message('This is a test message');

$adapter = new Apns($serverKey, AdapterInterface::ENVIRONMENT_DEVELOPMENT);

$pusher = new Pusher([new Push($adapter, $devices, $message)]);
$pusher->push();
```
