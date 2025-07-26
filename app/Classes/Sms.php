<?php

namespace App\Classes;

class Sms
{
    protected $key;

    public function __construct()
    {
        $this->key = env('SMS_KEY');
    }

    public function send()
    {
        if ($this->key) {
            // do
        } else {
            throw new Exception('Sms key is null...');
        }
    }
}
