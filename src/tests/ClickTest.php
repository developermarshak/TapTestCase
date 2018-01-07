<?php
namespace Tests;
use App\Models\Click;
use Faker\Provider\UserAgent;
use Illuminate\Support\Str;

class ClickTest extends TestCase
{
    function testSuccessClick(){
        $userAgent = UserAgent::userAgent();
        $referer = "https://github.com/developermarshak";
        $param1 = Str::random();

        $this->get('/click/?param1='.$param1, [
            'User-Agent' => $userAgent,
            'Referer'    => $referer
        ])->seeStatusCode(201);

        $this->seeInDatabase('click', [
            'ua'       => $userAgent,
            'referer'  => $referer ,
            'param1'   => $param1,
            'param2'   => '',
            'error'    => 0,
            'bad_domain' => 0
        ]);

        $click = Click::query()->first();
        /**
         * @var Click $click
         */
        $this->seeHeader('location', "/success/".$click->id);

        return [
            'ua'       => $userAgent,
            'referer'  => $referer ,
            'param1'   => $param1
        ];
    }

    function testErrorDoubleClick(){
        $data = $this->testSuccessClick();

        $userAgent = $data['ua'];
        $referer  = $data['referer'];
        $param1    = $data['param1'];

        $this->get('/click/?param1='.$param1, [
            'User-Agent' => $userAgent,
            'Referer'    => $referer
        ])->seeStatusCode(204);

        $this->assertEquals(Click::query()->count(), 1);

        $this->seeInDatabase('click', [
            'ua'         => $userAgent,
            'referer'    => $referer ,
            'param1'     => $param1,
            'param2'     => '',
            'error'      => 1,
            'bad_domain' => 0
        ]);

        $click = Click::query()->first();
        /**
         * @var Click $click
         */

        $this->seeHeader('location', "/error/".$click->id);
    }

    function testErrorBadDomain(){
        (new \BadDomainSeeder())->run();
        $userAgent = UserAgent::userAgent();
        $referer = "https://".\BadDomainSeeder::DOMAINS[0]."/".Str::random();
        $param1 = 123;

        $this->badDomainTest($userAgent, $referer, $param1, 1);

        return [
            'ua'       => $userAgent,
            'referer'  => $referer ,
            'param1'   => $param1
        ];
    }

    function testSuccessSecondClick(){
        $this->testSuccessClick();

        $param1 = Str::random();
        $param2 = Str::random();

        $ua = UserAgent::userAgent();
        $referer = 'https://google.com/asdfasdf';
        $this->get('/click/?param1='.$param1.'&param2='.$param2, [
            'user-agent' => $ua,
            'referer'    => $referer
        ])->seeStatusCode(201);

        $this->assertEquals(Click::query()->count(), 2);

        $this->seeInDatabase('click', [
            'ua'         => $ua,
            'referer'    => $referer,
            'error'      => 0,
            'bad_domain' => 0,
            'param1'     => $param1,
            'param2'     => $param2
        ]);
    }

    function issetClickBadDomain(){
        $userAgent = UserAgent::userAgent();
        $referer = "https://".\BadDomainSeeder::DOMAINS[0]."/".Str::random();
        $param1 = 123;

        for($i = 1; $i <= 5; $i++){
            $this->badDomainTest($userAgent, $referer, $param1, $i);
        }
    }

    protected function badDomainTest($userAgent, $referer, $param1, $count){
        $this->get('/click/?param1='.$param1, [
            'user-agent' => $userAgent,
            'referer'    => $referer
        ])->seeStatusCode(204);

        $this->assertEquals(Click::query()->count(), 1);

        $click = Click::query()->first();

        /**
         * @var Click $click
         */
        $this->seeHeader('location', "/error/".$click->id);

        $this->seeInDatabase('click', [
            'ua'         => $userAgent,
            'referer'    => $referer ,
            'param1'     => $param1,
            'param2'     => '',
            'error'      => 0,
            'bad_domain' => $count
        ]);
    }
}