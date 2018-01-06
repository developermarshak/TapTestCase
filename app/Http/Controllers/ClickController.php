<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 1:07
 */

namespace App\Http\Controllers;


use App\BadDomain;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClickController extends Controller
{

    /**
     * GET /click/
     * @param Request $request
     * @return Response
     */
    public function click(Request $request){
        $this->validate($request, [
            'param1' => 'required|string|maxLen:1000',
            'param2' => 'string|maxLen:1000'
        ]);

        $userAgent = $request->header('User-Agent', null);
        $referrer  = $request->header('Referrer', null);
        $ip        = $request->ip();
        $param1    = $request->input('param1', null);
        $param2    = $request->input('param1', '');

        if(is_null($userAgent) || is_null($referrer)){
            return $this->emptyHeaderResponse(400);
        }
        $click = new Click([
            'ua'       => $userAgent,
            'ip'       => $ip,
            'referrer' => $referrer,
            'param1'   => $param1,
            'param2'   => $param2
        ]);

        try{
            $domain = $this->getDomain($referrer);
            if(BadDomain::isExist($domain)){
                $click->bad_domains = 1;
                $click->incrementOrInsert('bad_domains');
            }
            elseif($click->incrementOrInsert('error')){
                return $this->emptyHeaderResponse(201, [
                    'location' => "/success/".$click->id
                ]);
            }
        }
        catch (\Exception $e){
            return $this->emptyHeaderResponse(500);
        }
        return $this->emptyHeaderResponse(204, [
            'location' => "/error/".$click->id
        ]);
    }

    /**
     * GET /success/{clickId}
     * @param $clickId
     * @return Response
     */
    public function success($clickId){
        $click = Click::query()->find($clickId);

        if( is_null($click) ){
            $data = [
                'error' => 'Click not found'
            ];
            return new Response($data, 404);
        }
        $data = [
            'message' => 'You referrer click is success',
            'data'    => $click->toArray()
        ];

        return new Response($data, 200);
    }

    /**
     * GET /error/{clickId}
     * @param $clickId
     * @return Response
     */
    public function error($clickId){
        $click = Click::query()->find($clickId);

        if( is_null($click) ){
            $data = [
                'error' => 'Click not found'
            ];
            return new Response($data, 404);
        }
        /**
         * @var Click $click
         */

        if($click->error && $click->bad_domains){
            $message = 'You referrer domain in blocked or link used';
        }
        elseif($click->bad_domains){
            $message = 'You referrer domain in blocked';
        }
        elseif($click->error){
            $message = 'You referrer link used';
        }
        else {
            $data = [
                'error' => 'Not found click error'
            ];
            return new Response($data, 500);
        }


        $data = [
            'message' => $message,
            'data'    => $click->toArray()
        ];

        return new Response($data, 200);
    }

    /**
     * GET /
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList(){
        return Click::all(); //Good practices do it with pagination
    }

    protected function getDomain($referrer){
        $host = parse_url($referrer, PHP_URL_HOST);
        $host = preg_replace('/$www\./', '', $host);
        $a = 1;
        return $host;
    }
}