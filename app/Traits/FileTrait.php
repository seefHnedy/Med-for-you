<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{

    public function uploadFile($file, $path)
    {
        $name = rand(11111, 99999) . $file->getClientOriginalName();
        $name = str_replace(' ', '', $name);

        $file->storeAs('/public/' . $path, $name);

        $directory = storage_path('app/public/' . $path);
        $fullPath = $directory . '/' . $name;

        if (is_dir($directory)) {
            chmod($directory, 0777);
        }

        if (file_exists($fullPath)) {
            chmod($fullPath, 0777);
        }

        return $path . $name;
    }


    public function deleteFile($path)
    {
        if (Storage::disk('public')->exists(Storage::url($path))) {
            Storage::disk('public')->delete(Storage::url($path));
        }
    }
}
