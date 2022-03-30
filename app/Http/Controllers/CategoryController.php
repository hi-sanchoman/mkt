<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
  
class CategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = Category::all();
        return Inertia::render('categories', ['data' => $data]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'category' => ['required'],
            'image' => ['required'],
            'active' => ['required'],
        ])->validate();
  
        Category::create($request->all());
  
        return redirect()->back()
                    ->with('message', 'Category Created Successfully.');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'category' => ['required'],
            'image' => ['required'],
            'active' => ['required'],
        ])->validate();
  
        if ($request->has('category_id')) {
            Category::find($request->input('category_id'))->update($request->all());
            return redirect()->back()
                    ->with('message', 'Category Updated Successfully.');
        }
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('category_id')) {
            Category::find($request->input('category_id'))->delete();
            return redirect()->back();
        }
    }
}