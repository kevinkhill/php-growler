PhpGrowler
==========

A simple library to send growl messages to a remote server.

An indirect fork of [php-growl](https://github.com/tylerhall/php-growl) with some tweaks, based on the work of [TylerHall](https://github.com/tylerhall).

Usage
-----

Create a new connection
```
$conn = new GrowlConnection('Test App', '192.168.0.50');
    $conn->addNotification(['success', 'info', 'warning', 'danger'])
         ->register();
