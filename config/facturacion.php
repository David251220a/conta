<?php

return [

    // Links para QR
    'link_qr' => [
        'produccion' => 'https://ekuatia.set.gov.py/consultas/qr?',
        'test' => 'https://ekuatia.set.gov.py/consultas-test/qr?',
    ],

    'link_api' => [
        'produccion' => 'https://sifen.set.gov.py/de/ws/async/recibe-lote.wsdl',
        'test' => 'https://sifen-test.set.gov.py/de/ws/async/recibe-lote.wsdl',
    ],

    'link_consulta' => [
        'produccion' => 'https://sifen.set.gov.py/de/ws/consultas/consulta-lote.wsdl',
        'test' => 'https://sifen-test.set.gov.py/de/ws/consultas/consulta-lote.wsdl',
    ],

    'link_consulta_cdc' => [
        'produccion' => 'https://sifen.set.gov.py/de/ws/consultas/consulta',
        'test' => 'https://sifen-test.set.gov.py/de/ws/consultas/consulta',
    ],

    'qr_version' => 'nVersion=150&Id=',

    // Carpeta segura para firma digital
    'firma_key_path' => storage_path('app/keys/'),
];
