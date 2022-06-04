<?php

namespace app\models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * This is the class which methods are used to receive data from HH API
 *
 * @property string $auth
 * @property string $base_uri
 */
class ApiRepository
{
    private static string $auth = 'Bearer R50T87FESPFCVCVBLP0S6195FQFF49P6CLTNCJVQC7JT0NS2NSHOS5TUI3BNTQBL';
    private static string $base_uri = 'https://api.hh.ru/';

    /**
     * @throws GuzzleException
     * @return array
     *
     * Performs a GET-request to HH API with a given query
     */
    public static function request(string $query): array
    {
        $httpClient = new Client([
            'base_uri' => self::$base_uri,
            'http_errors' => false
        ]);

        $response = $httpClient->request('GET', $query,  [
            'headers' => [
                'Authorization' => self::$auth
            ]
        ]);

        return json_decode($response->getBody()->getContents(), 1);
    }
}