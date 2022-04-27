<?php


namespace App\Services;
use Twilio\Rest\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class SmsService
{

    private $params;
    private $twilioClient;

    public function __construct(ParameterBagInterface $params,Client $twilioClient)
    {

$this->params=$params;
$this->twilioClient=$twilioClient;
    }

public function sendSms($numberTo,$message)
{
    $parameterValue = $this->params->get('twilio_number');

    $this->twilioClient->messages->create($numberTo,
        array(
            'from' => $parameterValue,
            'body' => $message

        )
    );
}
}