<?php
function preguntarChatGPT($pregunta) {
    //API KEY DE CHATGPT
    $apikey = "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";

    //INICIAR LA CONSULTA DEL CURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.openai.com/v1/completions');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apikey,
    ));
    /*curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apikey,
    ]);*/

    //INICIAR EL JSON QUE ENVIARA A META
    curl_setopt($curl, CURLOPT_POSTFIELDS, "{
        \"model\": \"text-davinci-003\",
        \"prompt\": \"" . $pregunta . "\",
        \"max_tokens\": 4000,
        \"temperature\": 1.0
    }");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //OBTENEMOS EL JSON CON LA RESPUESTA
    $response = curl_exec($curl);
    curl_close($curl);
    $decoded_json = json_decode($response, false);

    //print_r($decoded_json);

    //DEVOLVEMOS LA RESPUESTA
    return trim($decoded_json->choices[0]->text);
}

//$respuesta = preguntarChatGPT("Quién es el presidente de Guatemala");
//echo $respuesta;