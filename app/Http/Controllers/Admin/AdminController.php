<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddAdminRequest;
use App\Http\Requests\Admin\AdminIdRequest;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\GetAdminsRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\Medicine\ImportMedicineExcelRequest;
use App\Service\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;

class AdminController extends Controller implements HasMiddleware
{
    public function __construct(public AdminService $service)
    {}

    public static function middleware()
    {
        return [
            new Middleware('admin.type:superAdmin', only: ['AddAdmin', 'GetAdmins','DeleteAdmin','UpdateAdmin']),
        ];
    }


    public function Login(AdminLoginRequest $request)
    {
        $data = Arr::only($request->validated(), ['email', 'password']);
        $admin = $this->service->Login($data);
        return $this->sendResponse('Admin Login Successfully', $admin);
    }

    public function Logout()
    {
        $this->service->Logout();
        return $this->sendResponse('Admin Logout Successfully', null);
    }

    public function AddAdmin(AddAdminRequest $request)
    {
        $data = Arr::only($request->validated(), ['name', 'email', 'password', 'type']);
        $this->service->AddAdmin($data);
        return $this->sendResponse('Admin Added Successfully');
    }

    public function GetAdmins(GetAdminsRequest $request){
        $data = Arr::only($request->validated(), ['search','type']);
        $admins = $this->service->GetAdmins($data);
        return $this->sendPagination('Admin Fetched Successfully',$admins);
    }

    public function DeleteAdmin(AdminIdRequest $request){
        $data = Arr::only($request->validated(), ['admin_id']);
        $this->service->DeleteAdmin($data);
        return $this->sendResponse('Admin Deleted Successfully');
    }

    public function UpdateAdmin(UpdateAdminRequest $request){
        $data = Arr::only($request->validated(), ['admin_id','name', 'email', 'password', 'type']);
        $this->service->UpdateAdmin($data);
        return $this->sendResponse('Admin Updated Successfully');
    }


}
