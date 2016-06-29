<?php

use Dropbox\Client;
use League\Flysystem\Filesystem;
use League\Flysystem\Dropbox\DropboxAdapter as Adapter;

class MacNotifier {

    protected $filesystem;

    public function __construct($appKey, $appSecret)
    {
        $client           = new Client($appKey, $appSecret);
        $adapter          = new Adapter($client);
        $this->filesystem = new Filesystem($adapter);
    }

    public function clearOutDotUnderscoreFiles()
    {
        $contents = $this->filesystem->listContents("", true);

        foreach ($contents as $file) {
            if (empty($file) || empty($file["filename"]) || $file["type"] != "file") {
                continue;
            }

            if (substr($file["filename"], 0, 2) != "._") {
                continue;
            }

            $this->filesystem->delete($file["path"]);
        }
    }

    public function notify($title, $body, $url = null, $sender = null)
    {
        $filename     = "Molekula/notifications/" . microtime(TRUE) . ".sh";
        $notification = $this->getNotification($title, $body, $url, $sender);

        $this->filesystem->write($filename, $notification);
    }

    public function uploadFile($file, $filename)
    {
        $stream = fopen($file, 'r+');
        $location = "Molekula/sql/" . $filename;

        $this->filesystem->putStream($location, $stream);
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
