<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Categories;

use App\Domain\Categories\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                return [
                    'value' => $category->name,
                    'label' => $category->name,
                ];
            })
            ->values();

        return response()->json([
            'data' => $categories,
            'message' => '',
            'errors' => null,
        ]);
    }
}

