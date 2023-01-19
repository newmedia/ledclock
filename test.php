<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

class WLED
{
    private $client;
    private $ip;
    private $port;

    public function __construct(string $ip, int $port = 80)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->client = new Client([
            'base_uri' => "http://{$this->ip}:{$this->port}/json/",
            'timeout' => 2.0,
        ]);
    }

    public function get(string $endpoint)
    {
        $response = $this->client->request('GET', $endpoint);
        return json_decode($response->getBody()->getContents());
    }

    public function post(string $endpoint, array $data)
    {
        $response = $this->client->request(
            'POST',
            $endpoint,
            ['json' => $data]
        );
        return json_decode($response->getBody()->getContents());
    }

    public function info()
    {
        return $this->get('info');
    }

    public function state()
    {
        return $this->get('state');
    }

    public function effects()
    {
        return $this->get('effects');
    }

    public function palettes()
    {
        return $this->get('palettes');
    }

    public function presets()
    {
        return $this->get('presets');
    }

    public function set(array $data)
    {
        return $this->post('state', $data);
    }
}

class clock {
    private $wled;

    public function __construct(WLED $wled)
    {
        $this->wled = $wled;
    }

    public function off(): stdClass
    {
        return $this->wled->set(['on' => false]);
    }

    public function on(): stdClass
    {
        return $this->wled->set(['on' => true]);
    }

    public function setSegment(int $segmentId, string $character, string $color): stdClass
    {
        $ledMap = [
            1 =>  [0,8],
            2 =>  [9,17],
            3 =>  [18,26],
            4 =>  [27,35],
            5 =>  [36,44],
            6 =>  [45,53],
            7 =>  [54,62],
        ];

        /**
         *    6
         *  5   7
         *    4
         *  1   3
         *    2
         */

        $map = [
            'a' => [1,2,3,4,6,7],
            'A' => [1,3,4,5,6,7],
            'b' => [1,2,3,4,5],
            'c' => [1,2,4],
            'C' => [1,2,5,6],
            'd' => [1,2,3,4,7],
            'E' => [1,2,4,5,6],
            'e' => [1,2,4,5,6,7],
            'f' => [1,4,5,6],
            'F' => [1,4,5,6],
            'g' => [2,3,4,5,6,7],
            'i' => [1],
            'I' => [1,5],
            'O' => [1,2,3,5,6,7],
            'o' => [1,2,3,4],
            'r' => [1,4],
            'R' => [1,5,6],
            'S' => [2,3,4,5,6],
            's' => [2,3,4,5,6],
            't' => [1,2,4,5],
            'T' => [1,2,4,5],

        ];

        // Determine whiche segments to turn on
        $start = $segmentId * 63;

        $data = [];
        $letterMap = $map[$character];
        foreach($letterMap as $ledId) {
            $data[] = (int)$ledMap[$ledId][0] + $start;
            $data[] = (int)$ledMap[$ledId][1] + $start;
            $data[] = $color;
        }

        $body = ['seg' => ['i' => $data]];

        return $this->wled->set($body);
    }

    public function setText(string $text, string $color): stdClass
    {
        $text = str_split($text);
        $segmentId = 0;
        foreach($text as $character) {
            $this->setSegment($segmentId, $character, $color);
            $segmentId++;
        }
    }
}

$clock = new clock(new WLED('192.168.1.201'));

$red = 'FF0000';
$green = '00FF00';
$blue = '0000FF';

print_r($clock->off());
print_r($clock->on());
//print_r($clock->setText('bAd', $red));
//print_r($clock->setText('good', $green));
//print_r($clock->setText('ERIC', $green));
//print_r($clock->setText('eric', $blue));
print_r($clock->setText('stEf', $blue));

