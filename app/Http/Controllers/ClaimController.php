<?php

namespace App\Http\Controllers;


use App\Exports\ClaimsExport;
use App\Models\Claim;
use App\Models\User;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    public function index()
    {
//        $govers = DB::table('city')->get()->all();
        $govers = [];
        foreach (DB::table('city')->get() as $gov) {
            $govers[$gov->id] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'deps' => DB::table('district')->where('city_id', $gov->id)->get()
            ];
        }
        $govers = collect($govers);
//        $govers=$this->convrtToArray($govers);
////dd( $govers);
//        $deps = DB::table('district')->get()->all();
//        $deps=$this->convrtToArray($deps);
        return view('user.claim-form', ['govers' => $govers]);
    }

    public function convrtToArray($std)
    {
        $result = [];
        foreach ($std as $obj) {
            $result[$obj->id] = $obj->name;
        }
        return $result;
    }

    public function view()
    {
        $filters[] = '';
        $user = auth()->user();
//        $claims = Claim::query()->orderByDesc('id');
//        if ($user->role === 1) {
////            $claims = Claim::paginate(10);
//        } else {
//            $claims->where('governorate', $user->governorate);
//        }
//        $claims = $claims->paginate(10);
        return view('admin.claims-table');

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'phone_number' => 'required|max:10',
            'store_name' => 'required',
            'governorate' => 'required',
            'department' => 'required',
            'claim_type' => 'required',
            'address' => 'required',
            'claim' => 'required',
            'attachment_url' => 'nullable',
            'claim_note', 'nullable',
            'attachment' => 'nullable',
        ]);

        session()->put('claim', $request->all());
    }

    public function thankyou()
    {
//        dd($claimId = session()->get('claim_id'));
        logger('here');
        if ($claimId = session()->get('claim_id')) {
            logger($claimId);
            $claim = Claim::find($claimId);
//            session()->remove('claim_id');
            if ($claim->attachment_url === null) {
                $url = '';
                return view('shared.new-view', ['claim' => $claim, 'url' => $url]);
            }
            $url = Storage::url($claim->attachment_url);
            return view('shared.new-view', ['claim' => $claim, 'url' => $url]);
        }
        logger($claimId);
        abort(403);
    }

    public function sendOtp(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'phone_number' => 'required|max:10',
            'store_name' => 'required',
            'governorate' => 'required',
            'department' => 'required',
            'claim_type' => 'nullable',
            'address' => 'required',
            'claim' => 'required',
            'attachment_url' => 'nullable',
            'claim_note', 'nullable',
            'attachment' => 'nullable',
        ]);
        $data = request()->all();
        if (request()->hasFile('attachment_url'))
            $data['attachment_url'] = Storage::putFile('public', $request->file('attachment_url'), 'public');
        session()->put('claim', $data);
        $otp = random_int(1000, 9999);
//        dd($otp);
        $tokenResponse = Http::withOptions([
            'verify' => false,
        ])->post('https://sms.vipparcparamotor.com/api/login', [
            'email' => 'complains@madfox.solutions',
            'password' => '=k#%3tpe3([HGZ<t',
        ]);
        $tokenResponse = $tokenResponse->json();
        $jwtToken = $tokenResponse['token'];

        $client = new Client();
        $response = $client->get('https://sms.vipparcparamotor.com/send', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $jwtToken,
            ],
            'query' => [
                'phone_numbers' => "$request->phone_number",
                'body' => "Code : $otp",
            ],
            'verify' => false
        ]);
//        $smsResponse->
//        $smsResponse = $smsResponse->;

        $smsResponse = json_decode((string)$response->getBody(), 1);
        if ($smsResponse['success']) {
            Log::info("otp = " . $otp);
            session()->put('otp_code', $otp);
            return response()->json([
                'success',
            ]);
        } else {

        }

//        random_int(1000,9999)

    }

    public function checkOtp(Request $request)
    {
        if (!session()->has('claim')) {
            return response()->json_encode([
                'success' => false,
                'error' => 'no claim has been found'
            ]);
        }
        $otp_code = session()->get('otp_code');
        $code = $request->input('otp');
        if ($code == $otp_code) {
            $claim = Claim::create(session()->get('claim'));
            $data = [];
            $data['code'] = sprintf('%02d', $claim->governorate) . sprintf('%02d', $claim->claim_type) . sprintf('%05d', $claim->id);
            $claim->update($data);
            session()->put('claim_id', $claim->id);
            session()->forget('claim');
            return response()->json([
                'success' => true,
                'redirect' => route('thankyou')
            ]);
        }
        return response()->json([
            'success' => false,
            'error' => 'الرجاء التحقق من رمز التأكيد'
        ]);
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
        $filters = [
            'code',
            'governorate',
            'claim_type',
            'department'
        ];
//        dd($request->all());
        foreach ($filters as $filter)
            if (request($filter)) {
                $claims->where($filter, request($filter));
            }
//        if (request('department')) {
//            $claims = $claims
//                ->join('district', 'claims.department', '=', 'district.id')
//                ->addSelect('district.name as district_name', 'claims.*');
//            $claims->where('district.name', 'Like', "%{$request->department}%");
//        }
        if (request('action') === 'export') {
            $claims = $claims->get();
            return \Excel::download(new ClaimsExport(compact('claims')), 'cliams.xlsx');
        }
        $claims = $claims->paginate(10);
//        $filters[] = 'department';
        return view('admin.claims-table', ['claims' => $claims, 'filters' => request()->only($filters), 'govers' => $govers]);
    }

    public function print(Claim $claim)
    {
        $logs = DB::table('logs')->where('claim_id', $claim->id)->get()->last();
        $html = view('print', compact('claim', 'logs'))->render();
        $arabic = new \ArPHP\I18N\Arabic();

        $p = $arabic->arIdentify($html);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($html, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $html = substr_replace($html, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }
        $pdf = \PDF::loadHTML($html)->setPaper('a4');
        return $pdf->download($claim->id . '.pdf');
    }

    public function logs(Claim $claim)
    {
        $logs = DB::table('model_logs')->paginate(10);
        return view('admin.logs', ['logs' => $logs]);
    }

}
