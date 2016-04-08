<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Content-Type', 'Accept', 'Authorization', 'X-Requested-With', 'X-CSRF-TOKEN'],
    'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'exposedHeaders' => ['Authorization'],
    'maxAge' => 0,
    'hosts' => [],
];

