<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Suppressed Hashes (Berdasarkan hasil scan terakhir)
    |--------------------------------------------------------------------------
    */
    'suppressed' => [
        // Composer Audit & Outdated
        'ea3eab8b7975', '12c1c3497214',
        
        // XSS Risks (Twill CMS)
        '78d0562e87c3', '6d79939b015f', '4734789f08ba', '6936e12094ac', 
        '0915ce24def2', '3a36feb836de', '262ac6434eeb', 'e7adabea92c7', 
        'def037fba927', '248980cc41b3',
        
        // Package Freshness
        'ae70250e06e8', '77215ff81485', 'e23d7b219c35', 'b8371c41ecdb',
        
        // Suspicious Autoload
        '5ccf732179e3', '4cea312c6487', 'f1703c6eb8f6', '27c4d0c527cf', 
        'dc64aadecdb1', '9694e9169220', '8a70c392772b', '8d03ea2755b8', 
        '77b951747ccf', '84b6a9a16a53', '7c1663f85d9d', '47d2b10663f6', 
        'f253f382f86b', 'dba989137eb5', '159349645d90', '13aee9384c69', 
        '930d67de1001', 'c0b33212e292', '72551120097f', '2421ac5dea18', 
        'db625b40c9f2', '2fc080fcd68d', 'ecf22213e748',
        
        // Abandoned
        '2a9cd5361baf', '6ed4d3895391', '01974fe11379', '04a33955f755',
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious Autoload Whitelist (Sesuai output Deep Audit)
    |--------------------------------------------------------------------------
    */
    'suspicious_autoload' => [
        'whitelist' => [
            'aws/aws-sdk-php', 'ezyang/htmlpurifier', 'laravel/agent-detector',
            'laravel/pao', 'laravel/prompts', 'mews/purifier', 'mockery/mockery',
            'mtdowling/jmespath.php', 'myclabs/deep-copy', 'nunomaduro/collision',
            'nunomaduro/termwind', 'phpunit/phpunit', 'pragmarx/google2fa',
            'psy/psysh', 'ralouphie/getallheaders', 'sebastian/global-state',
            'sebastian/type', 'spatie/laravel-activitylog', 'spatie/laravel-backup',
            'symfony/clock', 'symfony/string', 'symfony/translation', 'symfony/var-dumper'
        ],
    ],
];
