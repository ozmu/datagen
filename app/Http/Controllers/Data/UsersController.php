<?php

namespace App\Http\Controllers\Data;

use Hash;
use App\User;
use App\Models\TextUser;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return User::where('id', '!=', $request->user()->id)->get();
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return User::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->input('scope') == 'texts'){
            return User::find($id) ? User::find($id)->texts : [];
        }
        return User::find($id) ? User::find($id) : abort(403);
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
        $user = User::find($id);
        if ($user){
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($request->input('resetPassword')){
                $user->password = Hash::make('password');
            }
            if ($user->save()){
                return ["status" => 200, "message" => "Kullanıcı güncellendi!"];
            }
            return ["status" => 500, "message" => "Kullanıcı güncellenirken hata!"];
        }
        return ["status" => 404, "message" => "Kullanıcı bulunamadı!"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->input('scope') == 'texts'){
            $textUser = TextUser::where(['id' => $request->input('text_user_id'), 'user_id' => $id]);
            if ($textUser->count()){
                $deleted = $textUser->first()->delete();
            }
            return $deleted ? ["status" => 200] : ["status" => 404];
        }
        return User::destroy($id);
    }
}
