<?php

namespace App\Service\Admin;

use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use App\Models\Request;
use App\Traits\PerPageTrait;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ValidationException;

class AdminService
{
    use PerPageTrait;

    public function Login($data)
    {
        $admin = Admin::where('email', $data['email'])->first();
        if (!Hash::check($data['password'], $admin->password)) {
            throw new ValidationException('user password incorrect');
        }
        $admin->tokens()
            ->whereJsonContains('scopes', 'Admin')
            ->delete();
        $admin['token'] = $admin->createToken('authToken', ['Admin'])->accessToken;
        return $admin;
    }


    public function Logout()
    {
        $admin = \auth('Admin')->user();
        $admin->tokens()
            ->whereJsonContains('scopes', 'Admin')
            ->delete();
    }

    public function AddAdmin($data)
    {
        return Admin::create($data);
    }

    public function GetAdmins($data)
    {
        $perPage = $this->getPerPage();
        $admins = Admin::query();
        if (isset($data['type'])) {
            $admins = $admins->where('type', $data['type']);
        }
        if (isset($data['search'])) {
            $admins = $admins->where('name','like', '%'.$data['search'].'%');
        }
        $admins = $admins->orderBy('created_at', 'desc')->paginate($perPage)->toArray();
        return $admins;
    }

    public function DeleteAdmin($data)
    {
        $admin = Admin::find($data['admin_id']);
        return $admin->delete();
    }

    public function UpdateAdmin($data)
    {
        $admin = Admin::find($data['admin_id']);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        return $admin->update($data);
    }
}
