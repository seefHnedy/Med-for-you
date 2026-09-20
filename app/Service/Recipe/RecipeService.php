<?php

namespace App\Service\Recipe;

use App\Enums\RequestStatusEnum;
use App\Models\Medicine;
use App\Models\Notification;
use App\Models\Recipe;
use App\Models\RecipeMedicine;
use App\Models\Request;
use App\Models\User;
use App\Service\Notification\NotificationService;

class RecipeService
{

    public function __construct(public NotificationService $notificationService)
    {
    }

    public function AddRecipe($data)
    {
        $recipe = Recipe::create($data);
        $request = Request::find($data['request_id']);
        $medicines = $data['medicines'];
        $recipeMedicinesQty = 0;
        $recipeTotalPrice = 0.00;
        foreach ($medicines as $medicine) {
            $medicineTotalPrice = 0.00;
            $neededMedicine = Medicine::find($medicine['medicine_id']);
            $medicineTotalPrice = $neededMedicine->price * $medicine['qty'];
            $recipeMedicine = RecipeMedicine::create([
                'recipe_id' => $recipe->id,
                'medicine_id' => $neededMedicine->id,
                'price' => $neededMedicine->price,
                'qty' => $medicine['qty'],
                'total_price' => $medicineTotalPrice,
            ]);
            $recipeMedicinesQty += $recipeMedicine->qty;
            $recipeTotalPrice += $medicineTotalPrice;
            $neededMedicine->increment('order_qty', $recipeMedicine->qty);
        }
        $recipe->update([
            'medicines_qty' => $recipeMedicinesQty,
            'total_price' => $recipeTotalPrice,
        ]);
        $request->update([
            'status' => RequestStatusEnum::APPROVED
        ]);
        $notificationTitle = 'قبول طلب';
        $notificationBody = 'تم قبول الطلب رقم #' . $request->id;
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


    public function GetRecipe($data)
    {
        $recipe = Recipe::find($data['recipe_id']);
        $recipe->pendingMedicines = $recipe->RecipeMedicines()->whereNull('pharmacy_id')->get();
        return $recipe;
    }

    public function GetRecipeByRequest($data){
        $recipe = Recipe::with('RecipeMedicines')->where('request_id',$data['request_id'])->first();
        return $recipe;
    }
}
