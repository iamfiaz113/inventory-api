<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use DB;
use Mail;

class MyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            $prompValue = $request->query('prompt');
            session(['prompt' => $prompValue]);
            $redirectRoute = route('login');
            if ($request->getQueryString()) {
                $redirectRoute .= '?' . $request->getQueryString();
            }
            return redirect($redirectRoute);
        } else {
            if (Auth::check() && Auth::user()->role === 'user') {
                $transactions=DB::table('transactions')->where('user_id',Auth::user()->id)->get();
                if(count($transactions) > 0){ 
                $buyedtokens=$transactions[0]->tokens;
                $usertokens=Auth::user()->credits;
                $tokens_75_percent = 0.75 * $buyedtokens;
                $tokens_50_percent = 0.50 * $buyedtokens;
                $percent=$transactions[0]->percent_tokens_sendmail;
                $tid=$transactions[0]->id;
                if($usertokens <= $tokens_50_percent && $percent == 75)
                {
                    $data = [
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'totaltokens' => $buyedtokens,
                        'remaintokens' => $usertokens,
                        'percent' => 50,
                    ];
                    Mail::send('Mail/usedtokens', $data, function ($message) use ($data) {
                        $message->to($data['email']);
                        $message->subject('Tokens Usage Notification');
                    });
                    DB::table('transactions')->where('id',$tid)->update(['percent_tokens_sendmail' => 50]);
                    DB::table("notifications")->insert([
                        'user_id' =>Auth::user()->id,
                        'message' => 'You have used your 50% Tokens',
                    ]);
                }
                if($usertokens <= $tokens_75_percent && $percent == 0 )
                {
                    $data = [
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'totaltokens' => $buyedtokens,
                        'remaintokens' => $usertokens,
                        'percent' => 75,
                    ];
                    Mail::send('Mail/usedtokens', $data, function ($message) use ($data) {
                        $message->to($data['email']);
                        $message->subject('Tokens Usage Notification');
                    });
                    DB::table('transactions')->where('id',$tid)->update(['percent_tokens_sendmail' => 75]);
                    DB::table("notifications")->insert([
                        'user_id' =>Auth::user()->id,
                        'message' => 'You have used your 75% Tokens',
                    ]);
                }
                if($usertokens <= 0  && $percent == 50)
                {
                    $data = [
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'totaltokens' => $buyedtokens,
                        'remaintokens' => $usertokens,
                        'percent' => 100,
                    ];
                    Mail::send('Mail/usedtokens', $data, function ($message) use ($data) {
                        $message->to($data['email']);
                        $message->subject('Tokens Usage Notification');
                    });
                    DB::table('transactions')->where('id',$tid)->update(['percent_tokens_sendmail' => 100]);
                    DB::table("notifications")->insert([
                        'user_id' =>Auth::user()->id,
                        'message' => 'You have used your 100% Tokens',
                    ]);
                }
                }
                $desiredRoute = $request->path();
                $featureCode = $this->getFeatureCodeForRoute($desiredRoute);
                if ($featureCode !== null) {
                    $transactions = DB::table('transactions')
                        ->join('pricing', 'transactions.pricing_id', '=', 'pricing.id')
                        ->where('transactions.user_id', Auth::user()->id)
                        ->select('transactions.*', 'pricing.features')
                        ->get();
                    if(count($transactions) > 0){    
                        $mergedFeatures = [];
                        foreach ($transactions as $transaction) {
                            $features = explode(',', $transaction->features);
                            $mergedFeatures = array_merge($mergedFeatures, $features);
                        }
                        $mergedFeatures = array_filter($mergedFeatures);
                        $uniqueFeatures = array_unique($mergedFeatures);
                        sort($uniqueFeatures);
                        if (in_array($featureCode, $uniqueFeatures)) {
                            return $next($request);
                        }else{
                        return response()->view('user.upgrade');
                        }
                    }else{
                        if($featureCode == 1 || $featureCode == 3){
                            return $next($request);
                        }else{
                            return $next($request);
                            return response()->view('user.upgrade');
                        }
                    }
                }else{
                    return $next($request);
                }
            } elseif (Auth::check() && Auth::user()->role === 'admin') {
                return redirect('/admin');
            } else {
                return redirect('/home');
            }
        }
    }

    /**
     * Get the feature code corresponding to the desired route.
     *
     * @param string $route
     * @return int|null
     */
    private function getFeatureCodeForRoute($route)
    {
        // Define the mapping of routes to feature codes
        $routeFeatureMap = [
            'generate-image' => 1,
            'image-remix' => 2,
            'super-resolution' => 3,
            'image-variations' => 4,
            'alter-image' => 5,
            // Add more routes as needed
        ];

        // Check if the route exists in the mapping
        return $routeFeatureMap[$route] ?? null;
    }
}
