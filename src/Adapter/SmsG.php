<?php


namespace Tartan\IranianSms\Adapter;


class SmsG extends AdapterAbstract implements AdapterInterface{

    public  $gateway_url;

    private $credential = [
        'user'   => '',
        'pass'   => '',
        'number' => '',
    ];

    public function __construct($account = null)
    {
        if (is_null($account)) {
            $this->gateway_url          = config('iranian_sms.smsg.gateway');
            $this->credential['user']   = config('iranian_sms.smsg.user');
            $this->credential['pass']   = config('iranian_sms.smsg.pass');
            $this->credential['number'] = config('iranian_sms.smsg.number');
        } else {
            $this->gateway_url          = config("iranian_sms.smsg.{$account}.gateway");
            $this->credential['user']   = config("iranian_sms.smsg.{$account}.user");
            $this->credential['pass']   = config("iranian_sms.smsg.{$account}.pass");
            $this->credential['number'] = config("iranian_sms.smsg.{$account}.number");
        }
    }

    public function send(string $number, string $text)
    {
        $number = $this->filterNumber($number);

        $params = [
            'goto' => 'webservice/json',
            'method' => 'send',
            'arg1' => $this->credential['user'],
            'arg2' => $this->credential['pass'],
            'arg3' =>$number,
            'arg4' => $this->credential['number'],
            'arg5' => $text
        ];

        $ch = curl_init($this->gateway_url . '?' . http_build_query($params)); // e.g. http://example.com/example.xml
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}