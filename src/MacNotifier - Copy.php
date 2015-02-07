<?php

use Barracuda\Copy\API;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Copy as Adapter;

class MacNotifier {

    protected $filesystem;

    protected $consumerKey    = "9gBiXqQX6gfPjineC2JZnDz2Kc0KailU";
    protected $consumerSecret = "RPWR4QxwK53o2IOxS2zB5HmRTyuephVNQKLdJ8raujCmSkwi";
    protected $accessToken    = "mVZyOEe72gYHDjOck4oASEIo4G4Lr4Wb";
    protected $tokenSecret    = "DMdaiW0Myy6E2pNmZT6V3SomktTSFU1v1WC1MILp8If9hDnj";

    public function __construct()
    {
        // $client           = new Client("_X7BXj2j1-gAAAAAAAAiD77QUiSeDKYy_ELbzqZ9b5emJAEdtrzTivVwK7s74v67", "djekl Notifications");
        $client           = new API($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->tokenSecret);
        $this->filesystem = new Filesystem(new Adapter($client));
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
