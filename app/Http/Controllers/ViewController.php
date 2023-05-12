<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ViewController extends Controller
{
    public function edit(Claim $claims)
    {
        $url = Storage::url($claims->attachment_url);
        $url_2 = Storage::url($claims->attachment);
        $logs = DB::table('logs')->where('claim_id', $claims->id)->get()->last();
        $options = ClaimType::all();
        return view('admin.admin-view', ['claims' => $claims, 'url' => $url, 'url_2' => $url_2, 'logs' => $logs, 'options' => $options]);
    }
    public function update(Request $request,Claim $claims){
        $this->validate($request, [
            'status' => 'required',
            'claim_note' => 'nullable',
            'claim_type' => 'required',
            'attachment' => 'nullable',
        ]);
        $data = request()->all();
        $data['claim_type_id'] = $data['claim_type'];
        if (request()->hasFile('attachment')){
            $data['attachment'] = Storage::putFile('public', $request->file('attachment'), 'public');
        }
        $claims->update($data);

        return response()->json([
            'success' => true,
            'redirect' => route('claim-table')
        ]);
//        return redirect()->route('claim-table');
    }

    public function addOption(Request $request)
    {
        $option = ClaimType::create($request->only('name'));
        return response()->json([
            'success' => true,
            'option' => $option
        ]);
    }
}
