<?php

namespace ACTCMS\Utility\Controller;

use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use ACTCMS\Utility\VugiChugi;

class UtilityController extends Controller{

    public function actcmsvnStart()
    {
        $pageTitle = VugiChugi::lsTitle();
        return view('Utility::actcmsvn_start',compact('pageTitle'));
    }

    public function actcmsvnSubmit(Request $request){
        $param['code'] = $request->purchase_code;
        $param['url'] = env("APP_URL");
        $param['user'] = $request->envato_username;
        $param['email'] = $request->email;
        $param['product'] = systemDetails()['name'];
        $reqRoute = VugiChugi::lcLabSbm();
        $response = CurlRequest::curlPostContent($reqRoute, $param);
        $response = json_decode($response);

        if ($response->error == 'error') {
            return response()->json(['type'=>'error','message'=>$response->message]);
        }

        $env = $_ENV;
        $env['PURCHASECODE'] = $request->purchase_code;
        $envString = '';
        $requiredEnv = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL', 'LOG_CHANNEL', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD','PURCHASECODE'];
        foreach($env as $k => $en){
if(in_array($k , $requiredEnv)){
$envString .= $k.'='.$en.'
';
}
        }

        $envLocation = substr($response->location,3);
        $envFile = fopen($envLocation, "w");
        fwrite($envFile, $envString);
        fclose($envFile);

        $actcmsvn = fopen(dirname(__DIR__).'/actcmsvn.json', "w");
        $txt = '{
    "purchase_code":'.'"'.$request->purchase_code.'",'.'
    "installcode":'.'"'.@$response->installcode.'",'.'
    "license_type":'.'"'.@$response->license_type.'"'.'
}';
        fwrite($actcmsvn, $txt);
        fclose($actcmsvn);

        $general = GeneralSetting::first();
        $general->maintenance_mode = 0;
        $general->save();

        return response()->json(['type'=>'success']);

    }
}
