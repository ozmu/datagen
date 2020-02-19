<?php

namespace App\Http\Controllers\Data;

use App\Models\Text;
use App\Models\TextUser;
use App\Models\Setting;
use App\Http\Requests\TextUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\TextsUsers\CreateJob;

class TextsUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $maximum_user_for_text = Setting::where('key', 'maximum_user_for_text')->first() ? (int)Setting::where('key', 'maximum_user_for_text')->first()->value : 10;
        $count = 0;
        while (true){
            $random = Text::all()->random();
            if (!in_array($random->id, $request->user()->texts->pluck('text_id')->toArray()) && $random->users()->get()->count() <= $maximum_user_for_text){
                break;
            }
            $count++;
            if ($count == Text::count()){
                return "All texts done!";
            }
        }
        return $random;
    }

    /**
     * Display last tagged texts
     * 
     * @return \Illuminate\Http\Response
     */
    public function last(Request $request){
        if ($request->input('scope') == 'all'){
            return [
                "data" => $request->user()->texts
            ];
        }
        return $request->user()->texts()->paginate($request->input('paginate') ? $request->input('paginate') : 5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TextUserRequest $request)
    {
        $created = TextUser::create([
            'user_id' => $request->user()->id,
            'text_id' => $request->input('text_id'),
            'tagged_text' => $request->input('tagged_text')
        ]);
        if ($created){
            //CreateJob::dispatch($created);
            return ["status" => 200, "message" => "Tagged text created successfully!"];
        }
        abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return TextUser::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TextUserRequest $request)
    {
        $updated = TextUser::find($request->input('text_id'))->update([
            'tagged_text' => $request->input('tagged_text')
        ]);
        if ($updated){
            return ["status" => 200];
        }
        return ["status" => 500];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $text = Text::where('id', $request->input('id'));
        if ($text->count() && in_array($text->first()->id, $request->user()->texts->pluck('id')->toArray())){
            return TextUser::destroy($id);
        }
        abort(403);
    }
}
