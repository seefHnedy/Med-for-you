<?php

namespace App\Service\Medicine;

use App\Http\Requests\Medicine\AddMedicineRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Traits\PerPageTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class MedicineService
{
    use PerPageTrait;

    public function ImportMedicineExcel($file)
    {
        $rows = Excel::toCollection(null, $file)->first() ?? collect();

        if ($rows->count() > 0) {
            $first = $rows->first();
            $rows = $rows->slice(1)->values();
        }

        $medicines = [];
        $batchSize = 500;

        foreach ($rows as $row) {
            $name_ar = $row['name_ar'] ?? $row[0] ?? null;
            $name_en = $row['name_en'] ?? $row[1] ?? null;
            $description = $row['description'] ?? $row[2] ?? null;
            $factory = $row['factory'] ?? $row[3] ?? null;
            $composition = $row['composition'] ?? $row[4] ?? null;
            $concentration = $row['concentration'] ?? $row[5] ?? null;
            $pharmaceutical_form = $row['pharmaceutical_form'] ?? $row[6] ?? null;
            $package = $row['package'] ?? $row[7] ?? null;
            $price = $row['price'] ?? $row[8] ?? 0.00;

            if ($name_ar === null || $name_en === null || $description === null || $price === null
                || $factory === null || $composition === null || $concentration === null
                || $pharmaceutical_form === null || $package === null) {
                continue;
            }

            $medicines[] = [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
                'description' => $description,
                'price' => (float)$price,
                'factory' => $factory,
                'composition' => $composition,
                'concentration' => $concentration,
                'pharmaceutical_form' => $pharmaceutical_form,
                'package' => $package,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($medicines) >= $batchSize) {
                Medicine::insertOrIgnore($medicines);
                $medicines = [];
            }
        }

        if (!empty($medicines)) {
            Medicine::insertOrIgnore($medicines);
        }
    }

    public function getAllSelector()
    {
        $Medicines = Medicine::all(['id', 'name_ar', 'name_en']);
        return $Medicines;
    }

    public function getOrderedMedicines($data)
    {
        $perPage = $this->getPerPage();
        $Medicines = Medicine::where('order_qty', '>', 0);
        if (isset($data['search'])) {
            $Medicines = $Medicines->where('name_ar', 'like', '%' . $data['search'] . '%');
        }
        $Medicines = $Medicines->orderBy('order_qty', 'desc')->paginate($perPage)->toArray();
        return $Medicines;
    }

    public function AddMedicine($data)
    {
        return Medicine::create($data);
    }

    public function DeleteMedicine($data)
    {
        $medicine = Medicine::find($data['medicine_id']);
        return $medicine->delete();
    }

    public function GetMedicines($data)
    {
        $perPage = $this->getPerPage();
        $medicines = Medicine::query();
        if (isset($data['search'])) {
            $medicines = $medicines->where('name_en', 'like', '%' . $data['search'] . '%');
        }
        $medicines = $medicines->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $medicines;
    }

    public function UpdateMedicine($data){
        $medicine = Medicine::find($data['medicine_id']);
        return $medicine->update($data);
    }
}
