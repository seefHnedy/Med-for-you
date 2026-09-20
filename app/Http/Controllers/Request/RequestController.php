<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\AddRequestRequest;
use App\Http\Requests\Request\GetRequestRecipeRequest;
use App\Http\Requests\Request\GetRequestsRequest;
use App\Http\Requests\Request\RejectRequestRequest;
use App\Service\Request\RequestService;
use Illuminate\Support\Arr;

class RequestController extends Controller
{

    public function __construct(public RequestService $service)
    {
    }

    public function AddRequest(AddRequestRequest $request){
        $data = Arr::only($request->validated(),['user_id','disease_name' ,'doctor_name' ,'visit_date' ,'recipe']);
        $this->service->AddRequest($data);
        return $this->sendResponse('Request Added Successfully');
    }

    public function RejectRequest(RejectRequestRequest $request){
        $data = Arr::only($request->validated(),['request_id','reject_reason']);
        $this->service->RejectRequest($data);
        return $this->sendResponse('Request Rejected Successfully');
    }

    public function GetRequests(GetRequestsRequest $request){
        $data = Arr::only($request->validated(),['status']);
        $requests = $this->service->GetRequests($data);
        return $this->sendPagination('Requests Fetched Successfully',$requests);
    }

    public function GetRequestRecipe(GetRequestRecipeRequest $request){
        $data = Arr::only($request->validated(),['request_id']);
        $recipe = $this->service->GetRequestRecipe($data);
        return $this->sendResponse('Recipe Fetched Successfully',$recipe);
    }
}
