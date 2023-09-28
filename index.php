<?php

/** Thanks To: Sandro Putraaa */

require __DIR__ . '/vendor/autoload.php';
use Curl\Curl;

class Tiktok {
    function __construct() {
        $this->curl = new Curl();
    }

    public function CheckService() {
        $this->curl->get("http://45.77.168.105:5020/api/services");
        if ($this->curl->error) {
            echo '[!] Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage . "\n\n";
        } else {
            $responseData = $this->curl->response;
            return $responseData;
        }
    }

    public function getInput($linkVideo, $service) {
        $this->curl->setHeader('Content-Type', 'application/json');
        $data = ['url' => $linkVideo, 'type' => $service];
        $this->curl->post("http://45.77.168.105:5020/api/link/insert", json_encode($data));
        if ($this->curl->error) {
            echo '[!] Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage . "\n\n";
        } else {
            $responseData = $this->curl->response;
            return $responseData;
        }
    }
}

function getInput(string $prompt): string {
    echo $prompt;
    return trim(fgets(STDIN));
}

$tiktok = new Tiktok;
$service = $tiktok->CheckService();
if ($service->message == "Success get services!") {
    $no = 1;
    echo "[-] List Service Active:\n";
    echo "-----------------------------------------------\n";
    foreach($service->services as $listService) {
        echo "    ".$no.". ".$listService->service_name." [".$listService->status."]\n";
        $no++;
    }
    echo "-----------------------------------------------\n";
}
$chooseInp = getInput("[?] Choose Menu: ");
echo "-----------------------------------------------\n";
if ($chooseInp == "1") {
    echo $service->services[0]->service_name."\n";
    while (true) {
        $linkVideo = getInput("[?] Url Video: ");
        $getInput = $tiktok->getInput($linkVideo, $service->services[0]->service_name);
        if($getInput->status == "success") {
            echo "[-] ".$getInput->message ."\n";
        } else {
            die("[-] ".$getInput->message);
        }
    }
} elseif($chooseInp == "2") {
    while (true) {
        $linkVideo = getInput("[?] Url Video: ");
        $getInput = $tiktok->getInput($linkVideo, $service->services[1]->service_name);
        if($getInput->status == "success") {
            echo "[-] ".$getInput->message ."\n";
        } else {
            die("[-] ".$getInput->message);
        }
    }
} elseif($chooseInp == "3") {
    while (true) {
        $linkVideo = getInput("[?] Url Video: ");
        $getInput = $tiktok->getInput($linkVideo, $service->services[2]->service_name);
        if($getInput->status == "success") {
            echo "[-] ".$getInput->message ."\n";
        } else {
            die("[-] ".$getInput->message);
        }
    }
} else {
    die("[!] Menu Not Found!!");
}
