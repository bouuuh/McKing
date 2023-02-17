<?php

namespace App\Classes;
use Mailjet\Client;
use Mailjet\Resources;

class Mail {
    private $api_key = 'e42bc923fd204946ae5e2682faa73c16';
    private $api_key_secret = 'e344d1b09be3b388e8def1abf22f47d9';

    public function send($to_email, $to_name, $subject, $title, $content){
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "candice.doise@gmail.com",
                        'Name' => "McKing"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4575118,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'title' => $title,
                        'content' => $content,
                    ]
                    
                ]
            ]
        ];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success();
    }}