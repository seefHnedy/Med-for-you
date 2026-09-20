<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medicine\AddRecipeRequest;
use App\Http\Requests\Recipe\GetRecipeByRequestRequest;
use App\Http\Requests\Recipe\GetRecipeRequest;
use App\Service\Recipe\RecipeService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecipeController extends Controller
{
    public function __construct(public RecipeService $service)
    {
    }

    public function AddRecipe(AddRecipeRequest $request)
    {
        $data = Arr::only($request->validated(), ['request_id', 'medicines','priority']);
        $this->service->AddRecipe($data);
        return $this->sendResponse('Recipe Added Successfully');
    }

    public function GetRecipe(GetRecipeRequest $request)
    {
        $data = Arr::only($request->validated(), ['recipe_id']);
        $recipe = $this->service->GetRecipe($data);
        return $this->sendResponse('Recipe Added Successfully',$recipe);
    }

    public function GetRecipeByRequest(GetRecipeByRequestRequest $request){
        $data = Arr::only($request->validated(), ['request_id']);
        $recipe = $this->service->GetRecipeByRequest($data);
        return $this->sendResponse('Recipe Fetched Successfully',$recipe);
    }
}
