<?php
/**
 * Configuration settings used by all of the examples.
 *
 * Specify your eBay application keys in the appropriate places.
 *
 * Be careful not to commit this file into an SCM repository.
 * You risk exposing your eBay application keys to more people than intended.
 */
return [
    'sandbox' => [
        'credentials' => [
            'devId' => '0655a9d6-9021-4a82-90cb-bf1d8fbb079e',
            'appId' => 'ElenaAle-test-SBX-5ca7fae94-c4c3b78e',
            'certId' => 'SBX-ca7fae941719-ded2-4ff8-8367-03f3',
        ],
        'authToken' => 'AgAAAA**AQAAAA**aAAAAA**4HQ5Xg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4aiCpSDow6dj6x9nY+seQ**dCsFAA**AAMAAA**fYxN6BD3iaGy0zfnjEB4Dxqt+gYUNyNNGRRY3K0a0FeSf2a/GA1gKFlwt6/QaWqUgGhFd7GTls5y32mv9KcVcK1PaypY/b8AawAd5DE4tYOPBrJ6VKfZULFpvdBjo2f+9EtyM6xwVOay/9PNcdFY9TiT8IxMEHOLbGBwXaqbtlKRnpPPkGc0FxYt34S2FfHz2/9yV4VCEB9tlRwscQ7zhr/iA0zBu2gBzPq0eQApohXP43bq4FL/rpNQzlY7XCMbmilPw0Rlcbi5pg1KdcK7/l8W//Ot6TXgmF/LOuIwN+66N6QWYfds1oHwjL1eC+o9nNFy4c4yIf7GXYrjm50oVcjt7AtqU+9owPmBzSmLUYrsFkz2LlcOUtV0+PD1cA7ZaAAFFMmSxm/Y4UFG6twBzi7atiuPc3GZcPCov3qAssTVc8af3qT1OBt2Es9mdMAUHONHLUhv5J8pv6+7sdWjopY0iQA+9W8oHIKeQxQPMYiI2D+jK0Cg1Erq9nEsWntWGR6UV0QBbHeP13zSD3UkTs2pMoAhm/KIYvwkJC6emrBUrCUxDgyOmPdpqb8Cniz92GtqE3ixIan1AB0ihUrBTHwgLzMHT3r+UFRcctYuJGsDZSbfvKoubTsCVZXBseoPhmplSYtrc8bBsIAtXMxfHkJL+zZdpZBaEtH18le4SxsmMdUAoUqWMMJLKyiMG24VgNdPRGHcjGcZOOFMPxJ51cdrKk4i0pnmij8UeT1CxbtbaPna8fRUWbZB0jzYnzUK',
        'oauthUserToken' => 'Elena_Aleksieva-ElenaAle-test-S-wyvcnfk',
        'ruName' => 'Elena_Aleksieva-ElenaAle-test-S-wyvcnfk'
    ],
    'production' => [
        'credentials' => [
            'devId' => 'YOUR_PRODUCTION_DEVID_APPLICATION_KEY',
            'appId' => 'YOUR_PRODUCTION_APPID_APPLICATION_KEY',
            'certId' => 'YOUR_PRODUCTION_CERTID_APPLICATION_KEY',
        ],
        'authToken' => 'YOUR_PRODUCTION_USER_TOKEN_APPLICATION_KEY',
        'oauthUserToken' => 'YOUR_PRODUCTION_OAUTH_USER_TOKEN',
        'ruName' => 'YOUR_PRODUCTION_RUNAME'
    ]
];
