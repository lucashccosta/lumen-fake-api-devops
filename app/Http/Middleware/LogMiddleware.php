<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use MongoDB\Client as MongoClient;

/**
 * Mongo LOG: save IP and AGENT
 */
class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $db = env('MONGO_DATABASE');
            $mongo = new MongoClient('mongodb://'.env('MONGO_USER').':'.env('MONGO_PASS').'@'.env('MONGO_HOST').':'.env('MONGO_PORT'));
            $collection = $mongo->$db->logs;
            $collection->insertOne([
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        catch(Exception $e) {
            dd($e);
        }
        
        return $next($request);
    }
}
