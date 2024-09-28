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

        error_log(json_encode($data));
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

    static public function sendFirstMessage($phone) {
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' =>  '+55' . $phone,
            'type' => 'interactive',
            "interactive" => [
                "type" => "list",
                "header" => [
                    "type" => "text",
                    "text" => "Escolha uma opção para inicial"
                ],
                "body" => [
                    "text" => "Escolha uma de nossas opções"
                ],
                "action" => [
                    "sections" => [
                        [
                            "title" => "Unidades",
                            "rows" => [
                                [
                                    "id" => "ofertas",
                                    "title" => "Ofertas",
                                ],
                                [
                                    "id" => "horarios",
                                    "title" => "Horários",
                                ],
                                [
                                    "id" => "Filiais",
                                    "title" => "Contatos",
                                ],
                            ]
                        ]
                    ],
                    "button" => "Escolher",
                ]
            ]
        ];

        Utils::sendCurlRequest($phone, $data);
    }

    static public function treatListReply($id, $phone) {
        $possible_response_initial_list = ['ofertas', 'horarios', 'filiais'];
        $possible_reponse_filial_list = ['vila_estrela', 'uvaranas'];

        if (in_array($id, $possible_response_initial_list)) {
            if ($id == 'ofertas') {
                error_log('ofertas');
                Utils::sendOffers($phone);
            }

            if ($id == 'horarios') {
                Utils::sendOpeningHours($phone);
            }

            if ($id == 'filiais') {
                Utils::sendFiliaisList($phone);
            }
        }

        if (in_array($id, $possible_reponse_filial_list)) {
            Utils::sendContact($id, $phone);
        }
    }
    
    static public function sendOffers($phone) {
        $offers = ["Arroz São João - R$30,00", "Feijão tropeiro - R$20,00", "Filé de peito Sadia - R$19,99"];

        $data = [
                "messaging_product" => "whatsapp",
                "recipient_type" =>  "individual",
                'to' =>  '+55' . $phone,
                "type" => "text",
                "text" =>  [
                  "preview_url" => "",
                  "body" => implode(",", $offers)
                ]
        ];

        Utils::sendCurlRequest($phone, $data);
    }

    static public function sendOpeningHours($phone) {
        $hours = ["Filial Vila estrela - 08:00h as 18:00h", "Filial Uvaranas - 09:00h as 20:00h"];

        $data = [    
            "messaging_product" => "whatsapp",
            "recipient_type" =>  "individual",
            'to' =>  '+55' . $phone,
            "type" => "text",
            "text" =>  [
              "preview_url" => "",
              "body" => $hours
            ]
    ];

    Utils::sendCurlRequest($phone, $data);
    }

    static public function sendFiliaisList($phone) {
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
