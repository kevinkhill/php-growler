<?php namespace Khill\PhpGrowler;

    class GrowlConnection
    {
        const UDP_PORT = 9887;

        private $appName;
        private $address;
        private $password;
        private $port;
        private $notifications;

        public function __construct($appName="PHP Growler", $address=null, $password=null, $port=null)
        {
            $this->appName       = utf8_encode($appName);
            $this->address       = $address;
            $this->password      = $password;
            $this->port          = is_null($port) ? self::UDP_PORT : $port;
            $this->notifications = array();

            if (is_null($this->address)) {
                throw new Exception('Address Missing', 'Unable to send notification without ip address.');
            }

            return $this;
        }

        public function getAppName()
        {
            return $this->appName;
        }

        public function getAddress()
        {
            return $this->address;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function getPort()
        {
            return $this->port;
        }

        public function getNotifications()
        {
            return $this->notifications;
        }

        public function addNotification($names)
        {
            if (is_array($names) && count($names) > 0) {
                foreach ($names as $notificationName) {
                    $this->notifications[] = array(
                        'name'    => utf8_encode($notificationName),
                        'enabled' => true
                    );
                }
            }

            return $this;
        }

        public function register()
        {
            $data        = '';
            $defaults    = '';
            $numDefaults = 0;

            for ($i = 0; $i < count($this->notifications); $i++) {
                $data .= pack('n', strlen($this->notifications[$i]['name'])) . $this->notifications[$i]['name'];

                if ($this->notifications[$i]['enabled']) {
                    $defaults .= pack('c', $i);
                    $numDefaults++;
                }
            }

            // pack(Protocol version, type, app name, number of notifications to register)
            $data  = pack('c2nc2', 1, 0, strlen($this->appName), count($this->notifications), $numDefaults) . $this->appName . $data . $defaults;
            $data .= pack('H32', md5($data . $this->password));

            return $this->send($data);
        }

        public function send($data)
        {
            if ((!defined('GROWL_SOCK') && function_exists('socket_create') && function_exists('socket_sendto')) || (GROWL_SOCK === 'socket')) {
                $sck = (strlen(inet_pton($this->address)) > 4 && defined('AF_INET6'))
                    ? socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP)
                    : socket_create(AF_INET,  SOCK_DGRAM, SOL_UDP);

                return socket_sendto($sck, $data, strlen($data), 0x100, $this->address, $this->port);
            } elseif ((!defined('GROWL_SOCK') && function_exists('fsockopen')) || (GROWL_SOCK === 'fsock')) {
                $fp  = fsockopen('udp://' . $this->address, $this->port);
                $ret = fwrite($fp, $data);
                fclose($fp);

                return $ret;
            }

            return false;
        }
    }

?>