<?php

namespace App\Service\PharmacyRequest;

use App\Enums\PharmacyRequestStatusEnum;
use App\Exceptions\ValidationException;
use App\Http\Requests\PharmacyRequest\VerifyPharmacyRequestRequest;
use App\Mail\SendDonationMail;
use App\Mail\SendPharmacyRequestMail;
use App\Models\PharmacyRequest;
use App\Models\PharmacyRequestMedicine;
use App\Models\Recipe;
use App\Models\RecipeMedicine;
use App\Traits\PerPageTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PharmacyRequestService
{
use PerPageTrait;

    public function AddPharmacyRequest($data)
    {
        $pharmacy = \auth('Pharmacy')->user();
        $recipe = Recipe::find($data['recipe_id']);
        $request = $recipe->Request;
        $user = $request?->User;
        $otp = \rand(111111, 999999);
        $data['otp'] = $otp;
        $data['pharmacy_id'] = $pharmacy->id;
        $pharmacyRequest = PharmacyRequest::create($data);
        $recipeMedicines = $data['recipe_medicines'];
        $qty = 0;
        $pharmacyRequestTotalPrice = 0.00;

        $medicinesArray = [];

        foreach ($recipeMedicines as $recipeMedicine) {
            $recipeMedicine = RecipeMedicine::find($recipeMedicine);

            PharmacyRequestMedicine::create([
                'pharmacy_request_id' => $pharmacyRequest->id,
                'medicine_id' => $recipeMedicine->medicine_id,
                'price' => $recipeMedicine->price,
                'qty' => $recipeMedicine->qty,
                'total_price' => $recipeMedicine->total_price,
            ]);

            $medicineName = $recipeMedicine->Medicine->name_ar ?? 'غير معروف';

            $medicinesArray[] = [
                'name' => $medicineName,
                'quantity' => $recipeMedicine->qty,
                'price' => $recipeMedicine->price,
                'total_price' => $recipeMedicine->total_price,
            ];

            $qty += $recipeMedicine->qty;
            $pharmacyRequestTotalPrice += $recipeMedicine->total_price;
        }

        $pharmacyRequest->update([
            'otp_expire_at' => \now()->addMinutes(10),
            'medicines_qty' => $qty,
            'total_price' => $pharmacyRequestTotalPrice,
        ]);

        Mail::to($user->email)->send(new SendPharmacyRequestMail($otp, $medicinesArray));

        return ['pharmacy_request_id' => $pharmacyRequest->id];
    }


    public function VerifyPharmacyRequest($data)
    {
        $pharmacyRequest = PharmacyRequest::find($data['pharmacy_request_id']);
        if (Carbon::parse($pharmacyRequest->otp_expire_at)->isPast()) {
            throw new ValidationException('OTP code expired');
        }
        if (!Hash::check($data['otp'], $pharmacyRequest->otp)) {
            throw new ValidationException('OTP code incorrect');
        }
        $pharmacyRequest->update([
            'status' => PharmacyRequestStatusEnum::APPROVED,
        ]);
        $recipeId = $pharmacyRequest->recipe_id;
        $medicineIds = $pharmacyRequest->PharmacyRequestMedicines()->pluck('medicine_id');
        RecipeMedicine::where('recipe_id', $recipeId)
            ->whereIn('medicine_id', $medicineIds)
            ->update([
                'pharmacy_id' => $data['pharmacy_id']
            ]);
    }

    public function GetPharmacyRequests($data){
        $perPage = $this->getPerPage();
        $pharmacy = \auth('Pharmacy')->user();
        $pharmacyRequests = PharmacyRequest::query()->where('pharmacy_id', $pharmacy->id);
        if (isset($data['status'])) {
            $pharmacyRequests = $pharmacyRequests->where('status', $data['status']);
        }
        $pharmacyRequests = $pharmacyRequests ->with('PharmacyRequestMedicines')->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $pharmacyRequests;
    }

    public function GetPharmacyRequestsAdmin($data){
        $perPage = $this->getPerPage();
        $pharmacyRequests = PharmacyRequest::query()->where('pharmacy_id', $data['pharmacy_id']);
        if (isset($data['status'])) {
            $pharmacyRequests = $pharmacyRequests->where('status', $data['status']);
        }
        $pharmacyRequests = $pharmacyRequests ->with('PharmacyRequestMedicines')->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $pharmacyRequests;
    }
}
