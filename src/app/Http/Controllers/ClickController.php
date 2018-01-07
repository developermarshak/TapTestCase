<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 1:07
 */

namespace App\Http\Controllers;


use App\Models\BadDomain;
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
            'param1' => 'required|string|max:1000',
            'param2' => 'string|max:1000'
        ]);

        $userAgent = $request->header('User-Agent', null);
        $referer  = $request->header('Referer', null);
        $ip        = $request->ip();
        $param1    = $request->input('param1', null);
        $param2    = $request->input('param2', '');

        if((is_null($userAgent) || is_null($referer))
        || (strlen($userAgent) > 1000 || strlen($referer) > 2100)){
           return new Response(['error' => 'Error validation referer or user-agent'], 400);
        }

        $click = new Click(
            [
                'ua'       => $userAgent,
                'ip'       => $ip,
                'referer'  => $referer,
                'param1'   => $param1,
                'param2'   => $param2
            ]
        );

        /**
         * @var Click $click
         */
        $domain = $this->getDomain($referer);

        if(BadDomain::isExist($domain)){
            $click->bad_domain = true;
            $click->updateOrInsert('bad_domain', false, true);
        }
        else{
            $click->updateOrInsert('error', true);
        }

        $click = Click::query()->find($click->id);

        if($click->bad_domain || $click->error){
            return $this->emptyHeaderResponse(301, [
                'Location' => "/error/".$click->id
            ]);
        }
        else{
            $response = $this->emptyHeaderResponse(301, [
                'Location' => "/success/".$click->id
            ]);
        }
        return $response;
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

        if($click->error && $click->bad_domain){
            $message = 'You referrer domain in blocked or link used';
        }
        elseif($click->bad_domain){
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
        return view('clicks', ['data' => Click::all()]);
    }

    protected function getDomain($referrer){
        $host = parse_url($referrer, PHP_URL_HOST);
        $host = preg_replace('/$www\./', '', $host);
        return $host;
    }
}