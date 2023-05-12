<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function index()
    {
        $claims = Claim::paginate(10);
        $govers = [];
        foreach (DB::table('city')->get() as $gov) {
            $govers[$gov->id] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'deps' => DB::table('district')->where('city_id', $gov->id)->get()
            ];
        }
        $govers = collect($govers);
        return view('user.table',['claims'=>$claims,'govers'=>$govers]);
    }
    public function search()
    {
        return view('user.search');
    }

    public function filter(Request $request)
    {
        $claims = Claim::query();
        $govers = [];
        foreach (DB::table('city')->get() as $gov) {
            $govers[$gov->id] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'deps' => DB::table('district')->where('city_id', $gov->id)->get()
            ];
        }
        $govers = collect($govers);
        $filters = [
            'code',
            'governorate',
            'claim_type',
        ];
        foreach ($filters as $filter)
            if (request($filter)) {
                $claims->where($filter, request($filter));
            }
        if (request('department')) {
            $claims = $claims
                ->join('district', 'claims.department', '=', 'district.id')
                ->addSelect('district.name as district_name', 'claims.*');
            $claims->where('department', 'Like', "%{$request->department}%");
        }

        $claims = $claims->paginate(10);
        $filters[] = 'department';
        return view('user.table', ['claims' => $claims, 'filters' => request()->only($filters), 'govers' => $govers]);

    }

    public function view(Request $request)
    {
        $search_id = $request->input('id');
        $search_phone = $request->input('phone_number');
        $claims = Claim::query();
        if (($search_id === null || $search_id == '') && ($search_phone === null || $search_phone == '')) {
            abort(404);
        } else {
            if (!($search_id === null || $search_id == '') || (!($search_id === null || $search_id == '') && !($search_phone === null || $search_phone == ''))) {
                $claims = $claims->where('code', $search_id)->first();
                if ($claims === null) {
                    abort(404);
                }
                return view('shared.old-view', ['claims' => $claims]);
            }
            if (!($search_phone === null || $search_phone == '')) {
                $claims = $claims->where('phone_number', $search_phone)->paginate(10)->appends($request->only(['phone_number', 'id']));
                if ($claims === null) {
                    abort(404);
                }
                return view('user.table', ['claims' => $claims]);
            }
        }
//        $claims = Claim::paginate(10);
//        $govers = [];
//        foreach (DB::table('city')->get() as $gov) {
//            $govers[$gov->id] = [
//                'id' => $gov->id,
//                'name' => $gov->name,
//                'deps' => DB::table('district')->where('city_id', $gov->id)->get()
//            ];
//        }
//        $govers = collect($govers);
//        return view('user.table',['claims'=>$claims,'govers'=>$govers]);
    }

    public function edit(Claim $claims)
    {
        $url = Storage::url($claims->attachment_url);
        $url_2 = Storage::url($claims->attachment);
        $logs = DB::table('logs')->where('claim_id', $claims->id)->get()->last();
        return view('shared.old-view', ['claims' => $claims, 'url' => $url, 'url_2' => $url_2, 'logs' => $logs]);
    }
}
