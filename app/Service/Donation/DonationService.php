<?php

namespace App\Service\Donation;

use App\Enums\AccountStatusEnum;
use App\Enums\DonationStatusEnum;
use App\Enums\DonationTypeEnum;
use App\Enums\OTPTypeEnum;
use App\Exceptions\ValidationException;
use App\Http\Requests\Donation\GetUserDonationsRequest;
use App\Mail\SendDonationMail;
use App\Mail\SendOTPMail;
use App\Models\Donation;
use App\Models\Medicine;
use App\Models\Notification;
use App\Models\OTP;
use App\Models\Recipe;
use App\Models\User;
use App\Service\Notification\NotificationService;
use App\Traits\PerPageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DonationService
{
    use PerPageTrait;

    public function __construct(public NotificationService $notificationService)
    {
    }

    public function AddDonation($data)
    {
        $user = \auth('User')->user();
        $type = $data['type'];
        $qty = 0;
        $medicine = Medicine::find($data['medicine_id']);
        if ($type === DonationTypeEnum::PART) {
            $qty = $data['qty'];
        } else {
            $qty = $medicine->order_qty;
        }
        $data['qty'] = $qty;
        $otp = \rand(111111, 999999);
        $data['otp'] = $otp;
        $data['medicine_price'] = $medicine->price;
        $data['donation_price'] = $medicine->price * $qty;
        $donation = Donation::create($data);
        Mail::to($user->email)->send(new SendDonationMail($otp, $medicine->name_ar, $data['qty'], $medicine->price));
        return ['donation_id' => $donation->id];
    }

    public function VerifyDonation($data)
    {
        $donation = Donation::find($data['donation_id']);
        if (Carbon::parse($donation->otp_expire_at)->isPast()) {
            throw new ValidationException('OTP code expired');
        }
        if (!Hash::check($data['otp'], $donation->otp)) {
            throw new ValidationException('OTP code incorrect');
        }
        $donation->update([
            'status' => DonationStatusEnum::APPROVED,
        ]);
        $donation->Medicine?->decrement('order_qty', $donation->qty);
        $donation->Medicine?->increment('available_qty', $donation->qty);
        DB::statement('CALL process_medicine_donation(?)', [$donation->Medicine?->id]);
        $results = DB::table('temp_recipe_results')->get();
        $recipeIds = $results->pluck('recipe_id')->toArray();

        foreach ($recipeIds as $recipeId) {
            $recipe = Recipe::find($recipeId);

            if (!$recipe || !$recipe->Request) {
                continue;
            }

            $user = User::find($recipe->Request->user_id);
            $notificationTitle = 'تم التبرع بالوصفة';
            $notificationBody = 'تم التبرع بجميع أدوية الطلب رقم #' . $recipe->Request->id;
            Notification::create([
                'user_id' => $recipe->Request->user_id,
                'title' => $notificationTitle,
                'body' => $notificationBody
            ]);

            if ($user && $user->fcm_token) {
                $this->notificationService->sendNotification(
                    $user->fcm_token,
                    $notificationTitle,
                    $notificationBody
                );
            }
        }

        DB::table('temp_recipe_results')->truncate();
    }


    public function GetMyDonations($data)
    {
        $perPage = $this->getPerPage();
        $user = \auth('User')->user();
        $donations = Donation::query()->where('user_id', $user->id);
        if (isset($data['status'])) {
            $donations = $donations->where('status', $data['status']);
        }
        $donations = $donations->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $donations;
    }

    public function GetUserDonations($data)
    {
        $perPage = $this->getPerPage();
        $donations = Donation::query();
        if (isset($data['status'])) {
            $donations = $donations->where('status', $data['status']);
        }

        $donations = $donations->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $donations;
    }


}
