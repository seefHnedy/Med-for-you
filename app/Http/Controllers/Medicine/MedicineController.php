<?php

namespace App\Http\Controllers\Medicine;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medicine\AddMedicineRequest;
use App\Http\Requests\Medicine\GetMedicinesRequest;
use App\Http\Requests\Medicine\GetOrderedMedicinesRequest;
use App\Http\Requests\Medicine\ImportMedicineExcelRequest;
use App\Http\Requests\Medicine\MedicineIdRequest;
use App\Http\Requests\Medicine\UpdateMedicineRequest;
use App\Models\Medicine;
use App\Service\Medicine\MedicineService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class MedicineController extends Controller implements HasMiddleware
{
    public function __construct(public MedicineService $service)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin,admin', only: ['ImportMedicineExcel', 'AddMedicine','DeleteMedicine','UpdateMedicine']),
        ];
    }

    public function ImportMedicineExcel(ImportMedicineExcelRequest $request)
    {
        $data = Arr::only($request->validated(), ['file']);
        $this->service->ImportMedicineExcel($data['file']);
        return $this->sendResponse('Medicines Imported Successfully');
    }

    public function getAllSelector()
    {
        $Medicines = $this->service->getAllSelector();
        return $this->sendResponse('Medicines Fetched Successfully', $Medicines);
    }

    public function getOrderedMedicines(GetOrderedMedicinesRequest $request)
    {
        $data = Arr::only($request->validated(), ['search']);
        $Medicines = $this->service->getOrderedMedicines($data);
        return $this->sendPagination('Medicines Fetched Successfully', $Medicines);
    }

    public function AddMedicine(AddMedicineRequest $request)
    {
        $data = Arr::only($request->validated(), ['name_ar', 'name_en', 'description', 'price', 'factory', 'composition', 'concentration', 'pharmaceutical_form', 'package']);
        $this->service->AddMedicine($data);
        return $this->sendResponse('Medicine Added Successfully');
    }

    public function DeleteMedicine(MedicineIdRequest $request)
    {
        $data = Arr::only($request->validated(), ['medicine_id']);
        $this->service->DeleteMedicine($data);
        return $this->sendResponse('Medicine Deleted Successfully');
    }

    public function GetMedicines(GetMedicinesRequest $request)
    {
        $data = Arr::only($request->validated(), ['search']);
        $medicines = $this->service->GetMedicines($data);
        return $this->sendPagination('Medicines Fetched Successfully', $medicines);
    }

    public function UpdateMedicine(UpdateMedicineRequest $request)
    {
        $data = Arr::only($request->validated(), ['medicine_id', 'name_ar', 'name_en', 'description', 'price', 'factory', 'composition', 'concentration', 'pharmaceutical_form', 'package']);
        $this->service->UpdateMedicine($data);
        return $this->sendResponse('Medicine Updated Successfully');
    }
}
