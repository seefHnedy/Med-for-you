<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Service\Notification\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(public NotificationService $service)
    {
    }

    public function GetMyNotification(){
        $notifications = $this->service->GetMyNotification();
        return $this->sendPagination('Notification Fetched Successfully',$notifications);
    }
}
