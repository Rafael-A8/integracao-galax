<?php
    require 'vendor/autoload.php';

    use App\GalaxPay\GalaxPay;

    $obGalax = new GalaxPay('5473','83Mw5u8988Qj6fZqS4Z8K7LzOo1j28S706R0BeFe');

#region cod tests
    // echo "<pre>";
    // print_r($obGalax->getToken());
    // echo "</pre>";
    // exit;

    // echo "<pre>"; print_r($this->baseUrl.$endpoint.'?'.http_build_query($params)); echo "</pre>"; exit;
#//region tests

    //Retorna um array de clientes
//     $clientes = $obGalax->sendRequest('/customers',[
//         "startAt"   =>  0,
//         "status"    =>  "active",
//         "limit"     =>  50
//    ]);

    $tipoPagamento = 'boleto';

    $customer = [
        'myId'     => "ID-CL-2",
        'name'     => "Claudio da Saveiro",
        'document' => 74247050087,
        'emails'   => ["claudiosaveiro@gmail.com"],
            'Address' => [
                'zipCode' => 29145500,
                'street'  => "Rua k10",
                'number'  => 32,
                'neighborhood' => "Bairro dos caminhão",
                'city' => "CaminhãoLandya",
                'state' => "ES"
            ]
    ];
    
#formas de pagamento
    $card = [
        'Card' => [
            'myId'      => "SU-2",
            'number'    => 4111111111111111,
            'holder'    => "Jose Comprador",
            'expiresAt' => "2023-07",
            'cvv'       => 363
        ],
        'qtdInstallments' => 5
    
    ];

    $pix = [
        'Deadline' => [
            'type' => "minutes",
            'value' => 300
        ]
    ];

    $boleto = [
        'deadlineDays' => 0
    ];
# //formas de pagamento

    //Cria um novo clientes
    // $novoCliente = $obGalax->sendRequest('/customers', $customer, 'POST');

    //Edita clientes
    // $editaCliente = $obGalax->sendRequest('/customers/ID-CLI-1/myId', $customer, 'PUT');

    //Deleta clientes
    // $deletaCliente = $obGalax->sendRequest('/customers/ID-CLI-1/myId', [], 'DELETE');

    //Cobrança com plano
    $newSubscription = [
            'myId'                => "SU-2",
            'value'               => 11900,
            'quantity'            => 3,
            'periodicity'         => "monthly",
            'firstPayDayDate'     => "2023-07-16",
            'additionalInfo'      => "Assinatura de Plano Curso Em familia",
            'mainPaymentMethodId' => "boleto",
            'Customer'            => $customer
    ];

    //Cobrança avulsa
    $newCharges = [
            'myId'                => "SU-4",
            'value'               => 11900,
            'payday'              => "2023-07-16",
            'mainPaymentMethodId' => $tipoPagamento,
            'Customer'            => $customer,
    ];

    if ($tipoPagamento == 'creditcard') {
        $newCharges['PaymentMethodCreditCard'] = $card;
    } elseif ($tipoPagamento == 'boleto') {
        $newCharges['PaymentMethodBoleto'] = $boleto;
    } elseif ($tipoPagamento == 'pix') {
        $newCharges['PaymentMethodPix'] = $pix;
    }

    //Cadastra cliente com plano clientes
    // $newSubcriptionRequest = $obGalax->sendRequest('/subscriptions', $newSubscription, 'POST');

    //Cobrança avulsas
    $newChargesRequest = $obGalax->sendRequest('/charges', $newCharges, 'POST');

    echo "<pre>"; print_r($newChargesRequest); echo "</pre>"; exit;
