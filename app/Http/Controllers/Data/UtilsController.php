<?php

namespace App\Http\Controllers\Data;

use Hash;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\User;
use App\Models\Entity;
use App\Models\Setting;
use App\Http\Requests\User\UpdateRequest as UpdateUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UtilsController extends Controller
{
    public function getUser(Request $request){
        return $request->user();
    }
    
    public function updateUser(UpdateUserRequest $request){
        $user = $request->user();
        if (User::where('id', '!=', $user->id)->where('email', $request->input('email'))->count()){
            return ["status" => 400, "message" => "Eposta zaten kayıtlı!"];
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('new_password')){
            if (Hash::check($request->input('current_password'), $user->password)){
                $user->password = Hash::make($request->input('new_password'));
            }
            else {
                return ["status" => 404, "message" => "Parola yanlış!"];
            }
        }
        return $user->save() ? ["status" => 200, "message" => "Kullanıcı güncellendi!"] : ["status" => 500, "message" => "Güncellenirken hata!"];
    }
    
    public function entities(Request $request){
        return Entity::where('is_active', true)->get();
    }

    public function widgets(Request $request){
        $scope = $request->input('scope');
        if ($scope == "balance"){
            return [
                "balance" => $request->user()->balance(),
                "notVerifiedBalance" => $request->user()->balance(false)
            ];
        }
        else if ($scope == "texts"){
            return [
                "today" => $request->user()->texts->where('created_at', '>', Carbon::today())->count(),
                "all" => $request->user()->texts->count()
            ];
        }
        else if ($scope == "tags"){
            return [
                "today" => $request->user()->tags()->where('created_at', '>', Carbon::today())->count(),
                "all" => $request->user()->tags()->count()
            ];
        }
    }

    public function charts(Request $request){
        $period = new CarbonPeriod(Carbon::create("1 week ago")->startOfDay(), "1 day", Carbon::today());
        $period = $period->toArray();
        array_push($period, Carbon::now());
        $scope = $request->input('scope');
        $data = collect();
        for ($p = 0; $p < count($period) - 1; $p++){
            $current = $period[$p];
            $next = $period[$p + 1];
            if ($scope == "gains"){
                $coin_factor = Setting::where('key', 'coin_factor')->first() ? (float) Setting::where('key', 'coin_factor')->first()->value : 1;
                $balance_calculation_type = Setting::where('key', 'balance_calculation_type')->first() ? Setting::where('key', 'balance_calculation_type')->first()->value : "verified_texts";
                if ($balance_calculation_type == "verified_tags") {
                    $data->push([
                        $current->format('d-M-y'),
                        $request->user()->tags()->whereBetween('verified_at', [$current, $next])->where('is_verified', true)->count() * $coin_factor
                    ]);
                }
                else {
                    // Verified Texts by default!
                    $data->push([
                        $current->format('d-M-y'),
                        $request->user()->texts->whereBetween('verified_at', [$current, $next])->where('is_verified', true)->count() * $coin_factor
                    ]);
                }
            }
            else if ($scope == "texts"){
                $data->push([
                    "label" => $current->format('d M'),//$current->format('d') . "-" . $next->format('d M'),
                    "value" => $request->user()->texts->where('created_at', '>', $current)->where('created_at', '<', $next)->count()
                ]);
            }
            else if ($scope == "tags"){
                if ($request->input('period') == "daily"){
                    $d = [
                        "start" => $current,
                        "end" => $next,
                        "data" => []
                    ];
                    $tags = [];
                    foreach($request->user()->tags()->where('created_at', '>', $current)->where('created_at', '<', $next) as $tag){
                        array_push($tags, empty($tag) ? null : $tag["entity_type"]["entity"]);
                    }
                    $d["data"] = array_count_values($tags);
                    $data->push($d);
                }
            }
        }
        if ($scope == "tags" && $request->input('period') == "all"){
            $tags = [];
            foreach($request->user()->tags() as $tag){
                array_push($tags, $tag["entity_type"]);
            }
            return collect($tags)->groupBy('entity')->map(function ($item, $key){
                return [
                    "count" => $item->count(),
                    "color" => $item[0]["color"]
                ];
            });
            $data->put('data', array_count_values($tags));
        }
        return $data;
    }
}
