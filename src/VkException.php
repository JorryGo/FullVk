<?php

namespace JorryGo\FullVk;

class VkException extends \Exception {

    public function __construct($message = "", $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);

        $this->logging($message);
    }

    private function logging($message)
    {

        $this->checkDir(Vk::$LOGS_DIRECTORY);

        $file = Vk::$LOGS_DIRECTORY . date('Y-m') . '_exception_log.txt';

        $msg_tpl = '[' . date('Y-m-d H:i:s') . '] ';
        $msg_tpl .= $message . PHP_EOL;

        file_put_contents($file, $msg_tpl, FILE_APPEND);
    }

    private function checkDir($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        if (Vk::$LOGS_DIRECTORY[mb_strlen(Vk::$LOGS_DIRECTORY) - 1] != '/') {
            Vk::$LOGS_DIRECTORY .= '/';
        }

    }
}