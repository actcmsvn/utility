<?php

namespace ACTCMS\Utility;

use Closure;

class GoToCore{

    public function handle($request, Closure $next)
    {
        $fileExists = file_exists(__DIR__.'/actcmsvn.json');
        if ($fileExists && env('PURCHASECODE')) {
            return redirect()->route(VugiChugi::acDRouter());
        }
        return $next($request);
    }
}
