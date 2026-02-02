<?php
if (isset($_POST['register_check'])) {
    print_r(org_get($_POST['register_check']));
}
function org_get($reg)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.ebarimt.mn/api/info/check/getTinInfo?regNo=$reg",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return $response;
    exit();
    $response = json_decode($response);
   

    $item = new stdClass();

    if ($response->status == '200') {
        $tin = $response->data;
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.ebarimt.mn/api/info/check/getInfo?tin=$tin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json"
            ],
        ]);

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $res = json_decode($res);
        //print_r($res);
        if ($res->status == '200') {

            $item->status = 200;
            $item->msg = $res->data->name;
            $item->tin = $tin;
            return $item;
        } else {
            $item->status = 500;
            $item->msg = $res->msg;
            return $item;
        }
    } else {
        $item->status = 500;
        $item->msg = $response->msg;
        return $item;
    }
}
