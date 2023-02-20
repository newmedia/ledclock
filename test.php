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

    public function getFxList(): array
    {
        $effects = $this->effects();
        $fxList = [];
        foreach ($effects as $key => $effect) {
            $fxList[$key] = $effect;
        }
        return $fxList;
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
            'h' => [1,3,4,5],
            'H' => [1,3,4,5,7],
            'i' => [1],
            'I' => [1,5],
            'j' => [2,3],
            'J' => [2,3,7],
            'k' => [1,3,4,5,7],
            'K' => [1,3,4,5,7],
            'l' => [1,5],
            'L' => [1,2,5],
            'm' => [1,3,4],
            'M' => [1,3,5,6,7],
            'n' => [1,3,4],
            'N' => [1,3,5,6,7],
            'O' => [1,2,3,5,6,7],
            'o' => [1,2,3,4],
            'P' => [1,4,5,6,7],
            'p' => [1,4,5,6,7],
            'q' => [3,4,5,6,7],
            'Q' => [3,4,5,6,7],
            'r' => [1,4],
            'R' => [1,5,6],
            'S' => [2,3,4,5,6],
            's' => [2,3,4,5,6],
            't' => [1,2,4,5],
            'T' => [1,2,4,5],
            'u' => [1,2,3],
            'U' => [1,2,3,5,7],
            'v' => [1,2,3],
            'V' => [1,2,3,5,7],
            'w' => [1,2,3],
            'W' => [1,2,3,5,7],
            '0' => [1,2,3,5,6,7],
            '1' => [1,5],
            '2' => [1,2,4,6,7],
            '3' => [2,3,4,6,7],
            '4' => [3,4,5,7],
            '5' => [2,3,4,5,6],
            '6' => [1,2,3,4,5,6],
            '7' => [3,6,7],
            '8' => [1,2,3,4,5,6,7],
            '9' => [2,3,4,5,6,7],

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

    public function setText(string $text, string $color): void
    {
        $text = str_split($text);
        $segmentId = 0;
        foreach($text as $character) {
            $this->setSegment($segmentId, $character, $color);
            $segmentId++;
        }
    }

    public function setFx(int $fxId): void
    {
        $this->wled->set(['seg' => ['fx' => $fxId]]);
    }
}


$wled = new WLED('192.168.188.54');

$clock = new clock($wled);

$red = 'FF0000';
$green = '00FF00';
$blue = '0000FF';

print_r($wled->getFxList());

$clock->off();
$clock->on();
$clock->setFx(44);
//print_r($clock->setText('bAd', $red));
//print_r($clock->setText('good', $green));
//print_r($clock->setText('ERIC', $green));
//print_r($clock->setText('eric', $blue));
//print_r($clock->setText('stEf', $blue));

