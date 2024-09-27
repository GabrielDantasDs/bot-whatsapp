<?php

namespace App\Helpers;

use Exception;

class Utils {
    static public function sendCurlRequest($phone, $data) {
        $url = config('app.whatsapp_url');
        $headers = [
            "Content-Type: application/json",
            'Authorization:Bearer ' . config('app.whatsapp_auth')
        ];
       

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Executa a requisição
        $response = curl_exec($ch);
        error_log('passou aqui');
        // Verifica se houve erro
        if (curl_errno($ch)) {
            error_log('error');
            throw new Exception('Erro no cURL: ' . curl_error($ch));
        }
        error_log(json_encode($response));
        // Fecha a conexão cURL
        curl_close($ch);

        return $response;
    }

    static public function sendContact($id, $phone) {
        if ($id == 'vila_estrela') {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => '+55'. $phone,
                "type" => "contacts",
                "contacts" => [
                    [
                        "name" => [
                            "formatted_name" => "Vila estrela",
                            "first_name" => "Vila estrela",
                        ],
                        "phones" => [
                            [
                                "phone" => "+5518981602270",
                                "type" => "work",
                                "wa_id" => "18981602270"
                            ]
                        ]
                    ]
                ]
            ];          
        }

        if ($id == 'uvaranas') {
            $data = [
                "messaging_product" => "whatsapp",
                "to" => '+55'. $phone,
                "type" => "contacts",
                "contacts" => [
                    [
                        "name" => [
                            "formatted_name" => "Uvaranas",
                            "first_name" => "Uvaranas",
                        ],
                        "phones" => [
                            [
                                "phone" => "+5518981602270",
                                "type" => "work",
                                "wa_id" => "18981602270"
                            ]
                        ]
                    ]
                ]
            ];
        }

        Utils::sendCurlRequest($phone, $data);
    }

    static public function sendFirstMessa($phone) {
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' =>  '+55' . $phone,
            'type' => 'interactive',
            "interactive" => [
                "type" => "list",
                "header" => [
                    "type" => "text",
                    "text" => "Escolha a unidade"
                ],
                "body" => [
                    "text" => "Escolha a unidade com a qual deseja conversar"
                ],
                "action" => [
                    "sections" => [
                        [
                            "title" => "Unidades",
                            "rows" => [
                                [
                                    "id" => "vila_estrela",
                                    "title" => "Estrela",
                                ],
                                [
                                    "id" => "uvaranas",
                                    "title" => "Uvaranas",
                                ]
                            ]

                        ]
                    ],
                    "button" => "Escolher",
                ]
            ]
        ];

        Utils::sendCurlRequest($phone, $data);
    }
}
