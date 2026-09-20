<?php

namespace App\Service\Notification;

use App\Models\Notification;
use App\Traits\PerPageTrait;
use Kreait\Firebase\Factory;

class NotificationService
{
    use PerPageTrait;

    public function GetMyNotification(){
        $perPage = $this->getPerPage();
        $user = \auth('User')->user();
        $notifications = Notification::where('user_id',$user->id)->paginate($perPage)->toArray();
        return $notifications;
    }

    public function sendNotification($fcm_token, $title = 'title', $body = 'body')
    {
        try {
            $factory = (new Factory())
                ->withServiceAccount(public_path('medicinedonation-a033e-6deffbf9f5f0.json'));

            $messaging = $factory->createMessaging();

            $message = [
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'token' => $fcm_token,
            ];

            $response = $messaging->send($message);

            return [
                'success' => true,
                'message' => 'Notification sent successfully',
                'response' => $response
            ];

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


}
