<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait PushNotification
{
    /**
     * Send Push Notification
     * @param  string|array $data
     * @param  string|array $token
     * @return Illuminate\Http\JsonResponse
     */
    public function sendNotification($type, $id, $title, $content, $token){
        $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
        $data = [
            "registration_ids" => $token,
            "notification" => [
                "title" =>  $title,
                "body" =>   $content,
            ],
            "data" => [
                'id' => $id,
                'type' => $type
            ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        return json_decode($response);
    }
}
