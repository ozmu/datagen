<?php

namespace App\Http\Controllers\Data;

use Carbon\Carbon;
use App\Models\Text;
use App\Models\TextUser;
use App\Models\Draft;
use App\Models\Setting;
use App\Http\Requests\TextUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\TextsUsers\CreateJob;
use App\Jobs\TextsUsers\UpdateJob;

class TextsUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $text = Text::random($request->user()->id);
        return collect($text)->isEmpty() ? response(null, 204) : $text;
    }

    /**
     * Display drafts texts
     * @return \Illuminate\Http\Response
     */
    public function drafts(Request $request){
        return [
            "data" => $request->user()->drafts()->with('text')->get()
        ];
    }

    /**
     * Display last tagged texts
     * 
     * @return \Illuminate\Http\Response
     */
    public function last(Request $request){
        if ($request->input('scope') == 'all'){
            return [
                "data" => $request->user()->texts()->with('text', 'tags', 'tags.entityType')->get()
            ];
        }
        return $request->user()->texts()->with('text', 'tags', 'tags.entityType')->paginate($request->input('paginate') ? $request->input('paginate') : 5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TextUserRequest $request)
    {
        $draft = Draft::where(['user_id' => $request->user()->id, 'text_id' => $request->input('text_id')]);
        if ($request->input('draft') == 'true'){
            if ($draft->count()){
                $d = $draft->first();
                $d->tagged_text = $request->input('tagged_text');
                $updated = $d->save();
            }
            else {
                $updated = Draft::create([
                    'user_id' => $request->user()->id,
                    'text_id' => $request->input('text_id'),
                    'tagged_text' => $request->input('tagged_text')
                ]);
            }
            if ($updated){
                return ["status" => 200, "message" => "Taslak kaydedildi.", "time" => Carbon::now()];
            }
            return ["status" => 400, "message" => "Taslak kaydederken hata!"];
        }
        else {
            $created = TextUser::create([
                'user_id' => $request->user()->id,
                'text_id' => $request->input('text_id'),
                'tagged_text' => $request->input('tagged_text')
            ]);
            if ($created){
                CreateJob::dispatch($created)->onQueue('computing');
                if ($draft->count()){
                    $draft->first()->delete();
                }
                return ["status" => 200, "message" => "Metin başarıyla oluşturuldu!", "data" => $created];
            }
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return TextUser::where('id', (int) $id)->with('text', 'tags', 'tags.entityType')->first();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDraft(Request $request, $id)
    {
        return Draft::where('id', (int) $id)->with('text')->first();
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
        $textUser = TextUser::find($request->input('text_id'));
        if ($textUser){
            $beforeTaggedText = $textUser->tagged_text;
            $textUser->tagged_text = $request->input('tagged_text');
            $updated = $textUser->save();
            if ($updated){
                UpdateJob::dispatch(TextUser::find($request->input('text_id')), $beforeTaggedText)->onQueue('computing');
                return ["status" => 200, "message" => "Metin başarıyla güncellendi!", "data" => $updated];
            }
            return ["status" => 500, "message" => "Sunucu hatası!"];
        }
        return ["status" => 404, "message" => "Metin bulunamadı!"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDraft(Request $request)
    {
        $draft = Draft::where('id', $request->input('id'));
        if ($draft->count() && in_array($draft->first()->id, $request->user()->drafts->pluck('id')->toArray())){
            return Draft::destroy($request->input('id'));
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $text = TextUser::where('id', $request->input('id'));
        if ($text->count() && in_array($text->first()->id, $request->user()->texts->pluck('id')->toArray())){
            return TextUser::destroy($request->input('id'));
        }
        abort(403);
    }
}
