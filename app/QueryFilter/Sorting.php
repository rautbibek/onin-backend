<?php

namespace App\QueryFilter;
use Closure;

class Sorting{
    public function handel($request,Closure $next){
        if(!(request()->has('sort'))){
            return $next($request);
        }
        return $next($request);
        // $builder = $next($request);
        // return $builder;
    }
}