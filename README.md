# Pusher
Compatable: PHP 7+
## Installation
### Composer
`composer require mxp100/pusher`
### APNS certicates generate
Please see Wiki:
https://github.com/mxp100/Pusher/wiki/Generating-the-Apple-Push-Notification-SSL-certificate-on-Mac
## Examples
Example for FCM:
```php
$serverKey = '[server key]';
$deviceId = '[device token]';

$devices = new DeviceCollection([new Device($deviceId)]);
$message = new Message('This is a test message');

$adapter = new Fcm($serverKey);

$pusher = new Pusher([new Push($adapter, $devices, $message)]);
$pusher->push();
```
Example for GCM:
```php
$serverKey = '[path to certification]';
$deviceId = '[device token]';

$devices = new DeviceCollection([new Device($deviceId)]);
$message = new Message('This is a test message');

$adapter = new Gcm($serverKey, AdapterInterface::ENVIRONMENT_DEVELOPMENT);

$pusher = new Pusher([new Push($adapter, $devices, $message)]);
$pusher->push();
```
Example for APNS:
```php
$serverKey = '[path to certification]';
$deviceId = '[device token]';

$devices = new DeviceCollection([new Device($deviceId)]);
$message = new Message('This is a test message');

$adapter = new Apns($serverKey, AdapterInterface::ENVIRONMENT_DEVELOPMENT);

$pusher = new Pusher([new Push($adapter, $devices, $message)]);
$pusher->push();
```
