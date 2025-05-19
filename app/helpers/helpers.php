<?php

function base_url(){
    return APP_URL;
}


function assets(){
    echo APP_URL . "/public/assets";
}

function dist(){
    echo APP_URL . "/public/dist";
}

// Función para verificar si la solicitud es AJAX
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

//Encriptar Contraseñas
function encryptString($id)
{
    $encryption_key = base64_decode(ENC_KEY);
    $iv = openssl_random_pseudo_bytes(16); //16 bytes
    $encrypted = openssl_encrypt($id, 'aes-256-cbc', $encryption_key, 0, $iv);
    return urlencode(base64_encode($encrypted . '::' . $iv));
}

function decryptString($encrypted)
{
    $encryption_key = base64_decode(ENC_KEY);
    list($encrypted_data, $iv) = explode('::', base64_decode(urldecode($encrypted)), 2);
    if (strlen($iv) !== 16) {
        return false; // El IV no es válido
    }
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

//Encriptar Ids (Descifrado Cesar cipher)
function hmacEncrypt($id)
{
    $hash = hash_hmac('sha256', $id, ENC_KEY, true);
    return base64UrlEncode($hash . '::' . $id);
}

function hmacDecrypt($signedId)
{
    $decoded = base64UrlDecode($signedId);
    if (strpos($decoded, '::') === false) {
        return false; // El formato no es válido
    }

    list($hash, $id) = explode('::', $decoded, 2);
    $calculatedHash = hash_hmac('sha256', $id, ENC_KEY, true);

    if (hash_equals($hash, $calculatedHash)) {
        return $id;
    } else {
        return false; // Firma no válida
    }
}

function base64UrlEncode($input)
{
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($input));
}

function base64UrlDecode($input)
{
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $input));
}



// Función para cargar las configuraciones desde el archivo JSON
function cargar_configuraciones() {
    global $configFile;
    if (file_exists($configFile)) {
        $config = json_decode(file_get_contents($configFile), true);
        return $config ?: [];
    }
    return [];
}

// Función para guardar las configuraciones en el archivo JSON
function guardar_configuraciones($config) {
    global $configFile;
    $jsonConfig = json_encode($config, JSON_PRETTY_PRINT);
    file_put_contents($configFile, $jsonConfig);
}