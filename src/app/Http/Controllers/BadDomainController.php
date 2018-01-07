<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 15:51
 */

namespace App\Http\Controllers;


use App\Models\BadDomain;
use Illuminate\Http\Request;

class BadDomainController extends Controller
{
    /**
     * GET /bad_domains/
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFrontList(){
        return view('bad_domain', ['data' => BadDomain::all()]);
    }

    public function get($id){
        $badDomain = BadDomain::query()->find($id);
        if(!$badDomain){
            return $this->emptyHeaderResponse(404);
        }
        return $badDomain;
    }

    /**
     * POST /bad_domains/
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request){
        $badDomain = new BadDomain();

        $this->validate($request, [
            'name' => 'required|string|max:1000|unique:'.$badDomain->getTable()
        ]);

        $badDomain->fill($request->all());
        if(!$badDomain->save()){
            $this->emptyHeaderResponse(500);
        }
        return $this->emptyHeaderResponse(201, [
            'Location' => '/bad_domains/'.$badDomain->id
        ]);
    }

    /**
     * DELETE /bad_domains/{id}
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id){
        $query = BadDomain::query()->where('id','=', $id);

        if((clone $query)->limit(1)->count() < 1){
            return $this->emptyHeaderResponse(404);
        }

        if(!(clone $query)->delete()){
            return $this->emptyHeaderResponse(500);
        }

        return $this->emptyHeaderResponse(204);
    }
}