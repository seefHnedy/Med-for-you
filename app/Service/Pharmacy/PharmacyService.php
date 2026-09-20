<?php

namespace App\Service\Pharmacy;

use App\Exceptions\ValidationException;
use App\Models\Pharmacy;
use App\Traits\FileTrait;
use App\Traits\PerPageTrait;
use Illuminate\Support\Facades\Hash;

class PharmacyService
{
    use FileTrait, PerPageTrait;


    public function Login($data)
    {
        $pharmacy = Pharmacy::where('email', $data['email'])->first();
        if (!Hash::check($data['password'], $pharmacy->password)) {
            throw new ValidationException('Pharmacy password incorrect');
        }
        $pharmacy->tokens()
            ->whereJsonContains('scopes', 'Pharmacy')
            ->delete();
        $pharmacy['token'] = $pharmacy->createToken('authToken', ['Pharmacy'])->accessToken;
        return $pharmacy;
    }


    public function Logout()
    {
        $admin = \auth('Pharmacy')->user();
        $admin->tokens()
            ->whereJsonContains('scopes', 'Pharmacy')
            ->delete();
    }


    public function AddPharmacy($data)
    {
        $path = "Attachments/Pharmacies/";
        $data['license'] = $this->uploadFile($data['license'], $path);
        return Pharmacy::create($data);
    }

    public function GetPharmacies($data)
    {
        $perPage = $this->getPerPage();
        $pharmacies = Pharmacy::query();
        if (isset($data['search'])) {
            $pharmacies = $pharmacies->where('name', 'like', '%' . $data['search'] . '%');
        }
        $pharmacies = $pharmacies->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $pharmacies;
    }

    public function DeletePharmacy($data){
        $pharmacy = Pharmacy::find($data['pharmacy_id']);
        return $pharmacy->delete();
    }

    public function UpdatePharmacy($data){
        $pharmacy = Pharmacy::find($data['pharmacy_id']);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        return $pharmacy->update($data);
    }
}
