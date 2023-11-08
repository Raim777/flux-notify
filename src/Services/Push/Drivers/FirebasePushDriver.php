<?php

namespace Raim\FluxNotify\Push\Drivers;

use Raim\FluxNotify\Helpers\DeviceTokenHelper;
use Raim\FluxNotify\Push\Classes\PushNotification;
use Raim\FluxNotify\Push\Contracts\PushDriverInterface;
use Illuminate\Support\Facades\Http;

class FirebasePushDriver implements PushDriverInterface
{
    const URL = 'https://fcm.googleapis.com/fcm/send';
    const FCM_KEY = 'AAAA9zUsJKg:APA91bGpBlNepRs6SBpn73lyQmfDrJRdst_h6yEPbnQztjtHERIFCfOb_dOkdrnsIWC6F8esUdLi1-XJ8Hyl-J7Hcs-YR5G_OIdQBdp8MeSZCMhb2T62ir7W2SPDsbrcvaYOH3vNxL0i';

    public function notify(PushNotification $push)
    {
        $data = $this->prepare($push);

        $headers = [
            'Authorization' => 'key=' .(env('FCM_SERVER_KEY') ?? self::FCM_KEY),
            'Content-Type'  => 'application/json',
        ];
        $response  = Http::withHeaders($headers)
            ->post(self::URL, $data)->json();
        return [ $response,DeviceTokenHelper::DRIVER_FIREBASE];
    }

    public function prepare(PushNotification $push) {
        $tokens = $push->getReceiver()
            ->deviceTokens()
            ->whereNotNull('device_token')
            ->get()->pluck('device_token')->toArray();

        return [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $push->subject,
                "body"  => $push->text,
                "sound"  => $push->sound,
            ],
            'data' => $push->extra
        ];
    }
}
