<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $users = User::where('name', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $users = User::latest()->paginate($perPage);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label','id');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
//        dd($request);
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|string|max:255|email|unique:users',
                'password' => 'required',
                'role_id' => 'required'
            ]
        );

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        // Return Restaurant and Driver to next page.
        if ($request->role_id == 4){
            return redirect()->route('branch.create',['userId'=> $user->id, 'title' => $request->name]);
        }
        elseif ($request->role_id == 3){
            return redirect()->route('driver.create',['userId'=> $user->id, 'title' => $request->name]);
        }
        return redirect('admin/users')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label','id');
        $user = User::with('role')->select('id', 'name', 'email', 'role_id')->findOrFail($id);

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|string|max:255|email|unique:users,email,' . $id,
                'role_id' => 'required'
            ]
        );

        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user = User::findOrFail($id);
        $user->update($data);
        return redirect('admin/users')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'User deleted!');
    }

    public function activateUser($id)
    {
        $user = User::findorfail($id);
        $user->status = 1;
        $user->save();
        return redirect()->back()->with('flash_message', 'User activated!');
    }

    public function deactiveUser($id)
    {
        $user = User::findorfail($id);
        $user->status = 0;
        $user->save();
        return redirect()->back()->with('flash_message', 'User deactivated!');
    }

}
