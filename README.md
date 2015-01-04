PhpGrowler
==========

A simple library to send growl messages to a remote server.

An indirect fork of [php-growl](https://github.com/tylerhall/php-growl) with some tweaks, based on the work of [TylerHall](https://github.com/tylerhall).


Usage
-----

Create a new connection:
```
$conn = new GrowlConnection(
  'Test App',      // App Title
  '192.168.0.100'  // Address of Growl server
);
```     

Add some notification types to send:
```
$conn->addNotification('success');
```    

Register the connection: (This only needs to be done once, before any growls are sent)
```
$conn->register();
```    

Create a growler:
```
$growl = new Growler();
```    

Send a notification:
```
$growl->notify(
    'success',
    'Notification Title,
    'You just sent a remote growl!'
);
```   


Complete Example
================

```
require 'PhpGrowler.php';
require 'GrowlConnection.php';

use Khill\PhpGrowler\PhpGrowler;
use Khill\PhpGrowler\GrowlConnection;

$conn = new GrowlConnection('Test App', '192.168.0.50');
$conn->addNotification('success');
$conn->register();

$growl = new PhpGrowler($conn);
$growl->notify(
    'success',
    'Notification Title,
    'You just sent a remote growl!'
);
```
