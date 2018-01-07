<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 18:36
 */
use \Illuminate\Database\Seeder;
use App\Models\BadDomain;

class BadDomainSeeder extends Seeder
{
    const DOMAINS = [
        'google.com',
        'domain2.com',
        'domain3.com'
    ];
    function run(){
        foreach (static::DOMAINS as $domain){
            BadDomain::query()->create([
                'name' => $domain
            ]);
        }
    }
}