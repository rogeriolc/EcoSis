<?php

class cDropbox
{
    private $api_content = 'https://content.dropboxapi.com/2'; //dropbox api url
    private $api = 'https://api.dropboxapi.com/2'; //dropbox api url
    private $token = 'RNbi5tjKZ_UAAAAAAAAAAUNfOzdswXaB1f1fDrNTxyc2SPdM4lvofgFqOuKlkx9H'; //oauth token
    private $path = '/uploads';

    public function upload($file, $folder = null)
    {
        $basePath = $this->path;

        $filename = basename($file['name']);

        $path = (!is_null($folder)) ? $basePath .'/' . $folder . '/' . basename($filename) : $basePath . '/' . basename($filename);

        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: ' .
                json_encode(
                    array(
                        "path" => $path,
                        "mode" => "add",
                        "autorename" => true,
                        "mute" => false
                    )
                )

        );

        $ch = curl_init($this->api_content . '/files/upload');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);

        $path = $file['tmp_name'];
        $fp = fopen($path, 'rb');
        $filesize = filesize($path);

        curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //        curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo ($response . '<br/>');
        // echo ($http_code . '<br/>');

        curl_close($ch);

        if ($http_code == 200) {
            $fileData = json_decode($response);

            return $fileData;
        } else {
            echo ($response);
        }
    }

    public function get($path)
    {
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        );

        $ch = curl_init($this->api . '/files/get_temporary_link');

        $parameters = json_encode(
            array(
                "path" => $path
            )
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo ($response . '<br/>');
        // echo ($http_code . '<br/>');

        curl_close($ch);

        return json_decode($response);
    }

    public function download($path)
    {
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Dropbox-API-Arg: ' .
            json_encode(
                array(
                     "path" => $path
                )
            )
        );

        $ch = curl_init($this->api_content . '/files/download');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo ($response . '<br/>');
        // echo ($http_code . '<br/>');

        curl_close($ch);


        header('Content-type: ' . 'application/octet-stream');
        // header('Content-Disposition: ' . 'attachment; filename=' . $fileData->name);
        readfile($response);
    }

    public function delete($path)
    {
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        );

        $ch = curl_init($this->api . '/files/delete_v2');

        $parameters = json_encode(
            array(
                "path" => $path
            )
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo ($response . '<br/>');
        // echo ($http_code . '<br/>');

        curl_close($ch);
    }
}
