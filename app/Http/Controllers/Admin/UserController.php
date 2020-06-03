<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Authorizable;
use File;
use Str;
use ImageStorage;
use Excel;
use App\Imports\UserImport;

use App\Http\Requests\UserRequest;
use App\Http\Requests\ImportUserRequest;

class UserController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('id','!=',auth()->user()->id)->orderBy($request->orderBy ?? 'updated_at',$request->order??'DESC');
        if(!empty($request->date)){
            $date = explode(' - ',urldecode($request->date));
            $starDate = $date[0].' 00:00:00';
            $endDate = $date[1].' 23:59:59';
            $users = $users->whereBetween('created_at',[$starDate,$endDate]);
        }

        if(!empty($request->search)){
            $searchFields = ['id','name','alamat','ttl'];
            $users->whereLike($searchFields, $request->search);
        }
        $users = $users->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('admin.users.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
         // hash password
        $request->merge(['password' => bcrypt($request->password)]);
        $params = $request->except('_token','roles');
        if($request->has('photo')){
            $name = $params->name.'_'.time();
            ImageStorage::upload($request->photo,$name);
            $params['photo'] = 'storage/'.$name.'.webp';
        }else{
            $params['photo'] = "vendor/adminlte/img/avatar.png";
        }
        // Create the user
        if ($user = User::create($params) ) {
            $this->syncPermissions($request, $user);
            flash('User has been created.');
        } else {
            flash()->error('Unable to create user.');
        }

        return redirect()->route('users.index');
    }
    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        }

        $user->syncRoles($roles);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'id');
        return view('admin.users.form', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $params = $request->except('_token','roles');
        if($request->has('photo')){
            $name = $user->name.'_'.time();
            ImageStorage::upload($request->photo,$name);
            if($user->photo != "vendor/adminlte/img/avatar.png"){
                $imagePath = substr($user->photo,8);
                ImageStorage::delete($imagePath);
            }
            $params['photo'] = 'storage/'.$name.'.webp';
        }
        // Update user
        $user->fill($params);

        // Handle the user roles
        $this->syncPermissions($request, $user);

        $user->save();
        flash()->success('User has been updated.');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->id == $id) {
            flash()->warning(trans('admin.deletecurrentuser'))->important();
            return redirect()->back();
        }

        if( User::findOrFail($id)->delete() ) {
            flash()->success(trans('admin.deleteddata'));
        } else {
            flash()->success(trans('admin.deleteddataerror'));
        }

        return redirect()->back();
    }

    public function notifications()
    {
        return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
    }

    public function import_create()
    {
        return view('admin.users.import');
    }
    public function import_store(ImportUserRequest $request)
    {
        $path = $request->file('file')->store('public');
        $fileName = substr($path,7);
        $import = new UserImport();
        $import->import(public_path('/storage/'.$fileName));
        $errors = [];
        foreach ($import->failures() as $failure) {
            $row = $failure->row();
            $attribute = $failure->attribute();
            $error = implode(" \n ",$failure->errors());
            array_push($errors,"Baris ke $row dengan attribute $attribute memiliki kesalahan $error");
        }
        ImageStorage::delete($fileName);
        if($errors){
            return redirect('admin/users/import')->withErrors($errors);
        }else{
            return redirect()->route('users.index');
        }
    }
}
