<?php

namespace App\Http;

use App\Helpers\Utils;
use Exception;
use Illuminate\Http\Request;

class MessageWebhook {
    public function test(Request $request) {
        Utils::sendCurlRequest('18981602270', 'Olá Gabriel');
        $hub_challenge = $request->query('hub_challenge');
        return response($hub_challenge, 200);
    }

    public function handle(Request $request) {
        try {
            $messages = $request->entry[0]['changes'][0]['value']['messages'];
            $name = $request->entry[0]['changes'][0]['value']['contacts'][0]['profile']['name'];

            foreach ($messages as $message) {
                //$phone = $message['from'];
                $phone = '18981602270';

                $data = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" =>  "individual",
                    'to' =>  '+55' . $phone,
                    "type" => "text",
                    "text" =>  [
                        "preview_url" => "",
                        "body" => "Olá ". $name 
                    ]
                ];

                if ($message['type'] == 'text') {
                    Utils::sendCurlRequest($phone, $data);
                }

                if ($message['type'] == 'interactive') {
                    Utils::treatListReply($message['interactive']['list_reply']['id'], $phone);
                }
            }

            return response('Sucesso', 200);
        } catch (Exception $erro) {
            error_log(json_encode($erro->getMessage()));
        }
    }
}
