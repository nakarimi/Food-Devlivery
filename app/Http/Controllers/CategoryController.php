<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $category = Category::where('branch_id', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('category_id', 'LIKE', "%$keyword%")
                ->orWhere('title', 'LIKE', "%$keyword%")
                ->orWhere('thumbnail', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $category = Category::latest()->paginate($perPage);
        }

        return view('category.category.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = $this->dropdown_data();
        return view('category.category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'branch_id' => 'required',
			'status' => 'required',
			'title' => 'required'
		]);
        $requestData = $request->all();
        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'pending';

        $id = DB::table('categories')->insertGetId(
            ['branch_id' => $requestData['branch_id'],
            'status' => $requestData['status']
            ]);
        
        if ($id) {
            $details_id = DB::table('category_details')->insertGetId(
                ['category_id' => $id,
                'title' => $requestData['title'],
                'description' => $requestData['description'],
                'thumbnail' => save_file($request, 'thumbnail'),
                // 'contents' => $requestData['contents'],
                'details_status' => $status,
                ]);

            if (!$details_id) {
                Category::destroy($id);
            }
        }
        else {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, storing category data');
        } 

        return redirect('category')->with('flash_message', 'Category added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return view('category.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        
        $data = $this->dropdown_data($id);

        return view('category.category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'branch_id' => 'required',
			'status' => 'required',
			'title' => 'required'
		]);
        $requestData = $request->all();
        
        $category = Category::findOrFail($id);
        $category->update($requestData);

        // Also update the details table.
        // Todo, if edit is done by admin the status shoudl be approved otherwise it should be pending.
        $status = 'pending';
        
        $update = ['category_id' => $id,
        'title' => $requestData['title'],
        'description' => $requestData['description'],
        // 'contents' => $requestData['contents'],
        'details_status' => $status,
        ];

        // If there was a new image, use it otherwise get old image name.
        if ($request->file('logo')) {
            $update['thumbnail'] = save_file($request);
        } else {
            $update['thumbnail'] =  $category->categoryDetails->thumbnail;
        }

        // Update details.
        $details_id = DB::table('category_details')->insertGetId($update);

        if (!$details_id) {
            return redirect('branch')->with('flash_message', 'Sorry there is problem, updating category data');
        }

        return redirect('category')->with('flash_message', 'Category updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return redirect('category')->with('flash_message', 'Category deleted!');
    }

    /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false) {
        // Pass commissons for dropdown list form.
        // $data['commissions'] = Commission::all();

        // Pass Users for dropdown list form.
        $data['users'] = User::all();

        // Pass Item to view. (For Edit form)
        // $item = Item::findOrFail($id);
        $data['category'] = ($id) ? $category = Category::findOrFail($id) : null;

        return $data;
    }
}
