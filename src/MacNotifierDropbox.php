<?php

use Dropbox\Client;
use League\Flysystem\Filesystem;
use League\Flysystem\Dropbox\DropboxAdapter as Adapter;

class MacNotifierDropbox {

    protected $filesystem;

    public function __construct($appKey, $appSecret)
    {
        $client           = new Client($appKey, $appSecret);
        $adapter          = new Adapter($client);
        $this->filesystem = new Filesystem($adapter);
    }

    public function notify($title, $body, $url = null, $sender = null)
    {
        $filename     = "notifications/" . microtime(TRUE) . ".sh";
        $notification = $this->getNotification($title, $body, $url, $sender);

        $this->filesystem->write($filename, $notification);
    }

    protected function getNotification($title, $body, $url, $sender)
    {
        $args = [
            "-title"   => $title,
            "-message" => $body,
            "-open"    => $url,
            "-sender"  => $sender,
        ];

        $args_str = [];

        foreach ($args as $key => $value) {
            if (!$value) {
                continue;
            }

            $args_str[] = "{$key} '{$value}'";
        }

        return "terminal-notifier " . implode(" ", $args_str);
    }
}
