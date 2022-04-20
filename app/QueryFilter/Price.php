<?php

namespace App\QueryFilter;
use Closure;
class Price{
    public function handle($request ,Closure $next){
        if(request()->has('price')){
            $builder = $next($request);
            if(request()->price[0] <= 0 && request()->price[1] <= 0){
                return $builder;
            }
            elseif(request()->price[0] > 0 && request()->price[1] <= 0 ){
                return $builder->where('price','>=',request()->price[0]);
            }elseif(request()->price[0] <= 0 && request()->price[1] >= 0 ){
                return $builder->where('price','<=',request()->price[1]);
            }else{
                return $builder->whereBetween('price',request('price'));
            }
        }
        return $next($request);
        
        
     }
}