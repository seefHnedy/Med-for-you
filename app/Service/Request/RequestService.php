<?php

namespace App\Service\Request;

use App\Enums\RequestStatusEnum;
use App\Enums\UserTypeEnum;
use App\Exceptions\ValidationException;
use App\Models\Notification;
use App\Models\Request;
use App\Models\User;
use App\Service\Notification\NotificationService;
use App\Traits\FileTrait;
use App\Traits\PerPageTrait;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RequestService
{
    use FileTrait, PerPageTrait;

    public function __construct(public NotificationService $notificationService)
    {
    }

    public function AddRequest($data)
    {
        $path = "Attachments/Requests/";
        $data['recipe'] = $this->uploadFile($data['recipe'], $path);
        $request = Request::create($data);
        $notificationTitle = 'اضافة طلب جديد';
        $notificationBody = 'تم اضافة الطلب رقم #' . $request->id . ' سيتم ارسال اشعار جديد عند تغيير حالة الطلب';
        Notification::create([
            'user_id' => $data['user_id'],
            'title' => $notificationTitle,
            'body' => $notificationBody
        ]);
        $user = User::find($data['user_id']);
        if ($user && $user->fcm_token){
            $this->notificationService->sendNotification($user->fcm_token,$notificationTitle,$notificationBody);
        }
        return $request;
    }

    public function RejectRequest($data)
    {
        $request = Request::find($data['request_id']);
        $request->update([
            'status' => RequestStatusEnum::REJECTED,
            'reject_reason' => isset($data['reject_reason']) ? $data['reject_reason'] : null,
        ]);
        $notificationTitle = 'رفض طلب';
        $notificationBody = 'تم رفض الطلب رقم #' . $request->id . ' بسبب: ' . $request->reject_reason;
        Notification::create([
            'user_id' => $request->user_id,
            'title' => $notificationTitle,
            'body' => $notificationBody
        ]);
        $user = User::find($request->user_id);
        if ($user && $user->fcm_token){
            $this->notificationService->sendNotification($user->fcm_token,$notificationTitle,$notificationBody);
        }
    }

    public function GetRequests($data)
    {
        $routePrefix = request()->route()->getPrefix();
        $perPage = $this->getPerPage();
        $requests = Request::query();
        if ($routePrefix === 'api/user'){
            $user = \auth('User')->user();
            $requests = $requests->where('user_id',$user->id);
        }
        if (isset($data['status'])) {
            $requests = $requests->where('status', $data['status']);
        }
        $requests = $requests->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $requests;
    }


    public function GetRequestRecipe($data){
        $request = Request::find($data['request_id']);
        $recipe = $request->Recipe()->first();
        $recipe->qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode(json_encode($recipe->id));
        $recipe->completedMedicines = $recipe->RecipeMedicines()->whereNotNull('pharmacy_id')->get();
        $recipe->pendingMedicines = $recipe->RecipeMedicines()->whereNull('pharmacy_id')->get();
        return $recipe;
    }

}
