<?php 
namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
    private $api_key = "fe252154f0c5094a12f685fe4737efa9";
    private $api_key_secret ="bb1f0a435bb304a8cda40a5e0381b539";

    public function send($to_email,$to_name,$subject,$content)
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "pilot@mailjet.com",
                        'Name' => "Mailjet Pilot"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2378451,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }
}