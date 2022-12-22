<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class HttpClient
{
    public static function fetch($method, $url, $data = [], $files = [])
    {
        // jika method get, langung return response dengan method get
        if ($method == "GET") return Http::get($url)->json();

        // jika terdapat file, cilent berupa multipart
        if (sizeof($files) > 0) {

            //attach setiap file pada client
            $client = Http::asMultipart();

            foreach ($files as $key => $file) {
                $path = $file->getPathName();
                $name = $file->getClientOrigiinalName();

                //attach file
                $client->attach($key, file_get_contents($path), $name);
            }

            //fatch api
            return $client->post($url, $data);
        }

        return Http::post($url, $data);
    }
}
