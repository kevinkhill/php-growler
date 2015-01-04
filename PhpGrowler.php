<?php namespace Khill\PhpGrowler;

    class PhpGrowler
    {
        const PRIORITY_LOW        = -2;
        const PRIORITY_MODERATE   = -1;
        const PRIORITY_NORMAL     = 0;
        const PRIORITY_HIGH       = 1;
        const PRIORITY_EMERGENCY  = 2;

        private $growlConnection;

        public function __construct(\Khill\PhpGrowler\GrowlConnection $conn)
        {
            $this->growlConnection = $conn;
        }

        public function notify($name, $title, $message, $priority=0, $sticky=false)
        {
            $name     = utf8_encode($name);
            $title    = utf8_encode($title);
            $message  = utf8_encode($message);
            $priority = intval($priority);

            $flags = ($priority & 7) * 2;
            if($priority < 0) $flags |= 8;
            if($sticky) $flags |= 256;

            // pack(protocol version, type, priority/sticky flags, notification name length, title length, message length. app name length)
            $data  = pack('c2n5', 1, 1, $flags, strlen($name), strlen($title), strlen($message), strlen($this->growlConnection->getAppName()));
            $data .= $name . $title . $message . $this->growlConnection->getAppName();
            $data .= pack('H32', md5($data . $this->growlConnection->getPassword()));

            return $this->growlConnection->send($data);
        }
    }

?>