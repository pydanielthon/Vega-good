<?php

namespace App\Http\Controllers;

use App\Models\Hours;
use App\Models\Workers;
use App\Models\Contrahents;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use PDO;

use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Eloquent\Collection;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $user->assignRole('Super-admin');
        $users = User::all()->sortBy('id');
        // $roles= $users::with('roles')->get();
        // $user = auth()->user();

        //    dd(Role::all());
        $user = User::find(Auth::id());
        // $user->assignRole('Super-admin');
        if ($user->hasRole('Super-admin')) {
        } else {

            return redirect()->route('home');
        }
        //    if($user->hasRole("Super-admin")) {
        // $user->givePermissionTo('Can delete users');


        //    }

        return view(
            'users.list',
            [
                'users' => $users
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::id());
        if ($user->hasRole('Super-admin')) {
        } else {
            return redirect()->route('home');
        }
        return view('users.create');
    }

    public function createViaUsers(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|confirmed',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user->hasRole('Super-admin')) {
        } else {
            $user->assignRole('Super-admin');
        }
    $isAdd= $user->save();


    $user = User::find(Auth::id());

    if($isAdd){
        DB::table('logs')->insert([
            [
            'name'=>'Dodanie użytkownika',
            'created_at' =>date('d-m-Y H:i'),
            'user_id' =>  $user->id,
            'notes'=> $request->name." ".$request->email
        ],
        ]);
    }
      if($isAdd){
        return redirect('users')->with('status','Stworzono nowego uzytkownika');

      }else{
        return redirect('users')->with('status','Nie udalo sie stworzyc nowego uzytkownika');

      }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);


        User::create($request->all());

        return view('home')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $User)
    {
        $user = User::find(Auth::id());


        $P = Permission::all();
        return view('users.show', compact('User','P'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $User)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        // ]);
        // $User->update($request->all());
        $user = User::find(Auth::id());

        if ($user->hasRole('Super-admin')) {
        } else {
            return redirect()->route('home');
        }
        $P = Permission::all();
        // $Pa = Permission::find(14)->delete();

        // dd(count(Permission::All()));

        return view('users.edit', compact('User', 'P'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function update(Request $request, User $User)
    {
      if($User->email == $request->email){

      }else{
  $request->validate([
            'email' => 'unique:users|email',
        ]);

      }
        $User->syncPermissions();
        $perms = $request->input('perms');
        if ($perms) {
            foreach ($perms as $perm) {
                // print_r($perm);
              $isPerm= $User->givePermissionTo($perm);
            }
        }
        if ($request->input('superadmin')) {
           $superAdmin= $User->assignRole('Super-admin');
        } else {
          $superAdmin=  $User->removeRole('Super-admin');
        }
        $User->update($request->all());
        $user = User::find(Auth::id());


        //dopracowac
        if($User->getChanges() or $superAdmin or  $isPerm ){
            DB::table('logs')->insert([
                [
                'name'=>'Aktualizacja użytkownika',
                'created_at' =>date('d-m-Y H:i'),
                'user_id' =>  $user->id,
                'notes'=> $request->name." ".$request->email


            ],

            ]);
            return  back()->with('status', 'Profil zaktualizowany');

        }else{
         return  back()->with('status', 'Profil nie został zaktualizowany');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User)
    {
        $user_info = User::find($User->id);
       $test= $User->delete();
       $user = User::find(Auth::id());

if($test){
    DB::table('logs')->insert([
        [
        'name'=>'Usunięcie użytkownika',
        'created_at' =>date('d-m-Y H:i'),
        'user_id' =>  $user->id,
        'notes'=> $user_info->name." ".$user_info->email


    ],

    ]);
   return redirect()->route('users.index')->with('status', 'Użytkownik został usunięty');
}else{
    return redirect()->route('users.index')->with('status', 'Użytkownik nie został usunięty');
}
        // return back();
    }
}