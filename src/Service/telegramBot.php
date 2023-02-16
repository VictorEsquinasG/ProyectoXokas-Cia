<?php
/* 
namespace App\Service;

use DateTime;

// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;

class telegramBot
{
}
// https://api.telegram.org/bot5932157685:AAFtCIW9kL37CFXcDz8LCnuTRqVXmSvF6_g/setWebhook?url=https://webhosttelegrambot1.000webhostapp.com/telegramBot.php

$token = '5932157685:AAFtCIW9kL37CFXcDz8LCnuTRqVXmSvF6_g';
$website = 'https://api.telegram.org/bot' . $token;

$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];

switch ($message) {
    case '/start':
        $response = 'Me has iniciado';
        sendMessage($chatId, $response);
        break;
    case '/info':
        $response = 'Hola! Soy @victoreitor_bot';
        sendMessage($chatId, $response);
        break;
    case "/time":
        // GET TIME
        $ahora = new DateTime();
        $hora = $ahora->format('H') . ':' . $ahora->format('i') . ':' . $ahora->format('s');
        sendMessage($chatId, $hora);
        break;
    case "/hi":
        // GREET
        sendMessage($chatId, "Hola");
        break;
    case "/date":
        // GET DATE
        $hoy = new DateTime();
        $fecha = $hoy->format('d') . '-' . $hoy->format('m') . '-' . $hoy->format('Y');
        sendMessage($chatId, $fecha);
        break;
    case "/dice":
        // ROLL DICE
        dice($chatId);
        break;
    case "/audio":
        //TODO asignar un audio de STOCK
        //TEST 
        sendAudio($chatId,'');
        break;
    default:
        $response = 'No te he entendido';
        sendMessage($chatId, $response);
        break;
}

function sendMessage($chatId, $response)
{
    $url = $GLOBALS['website'] . '/sendMessage?chat_id=' . $chatId . '&parse_mode=HTML&text=' . urlencode($response);
    file_get_contents($url);
}

function sendPhoto($chatId, $picture)
{
    $url = $GLOBALS['website'] . '/sendPhoto?chat_id=' . $chatId . '&photo=' . $picture;
    file_get_contents($url);
}

function sendAudio($chatId, $voice)
{
    $url = $GLOBALS['website'] . '/sendPhoto?chat_id=' . $chatId . '&audio=' . $voice;
    file_get_contents($url);
}

function dice($id)
{
    $cara = rand(1, 6); // La cara del dado 1-6
    // sendMessage($id,$cara);
    switch ($cara) {
        case 1:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/1.png");
            break;
        case 2:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/2.png");
            break;
        case 3:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/3.png");
            break;
        case 4:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/4.png");
            break;
        case 5:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/5.png");
            break;
        case 6:
            sendPhoto($id, "https://webhosttelegrambot1.000webhostapp.com/images/6.png");
            break;  
    }
}
 */