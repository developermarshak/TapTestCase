<?php
ignore_user_abort(1);
require __DIR__.'/../../vendor/autoload.php';

class CollisionTestThread{
    const SYNC_FILE_PATH = __DIR__.'/microtime_start.sync';

    const USER_AGENT = "Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0";

    const REFFERER = "http://test.com/123";

    const PARAM1 = "123";

    const IP = '127.0.0.1';

    public $app;
    function __construct()
    {
        $this->app = $this->createApplication();
    }

    function createApplication(){
        return require __DIR__.'/../../bootstrap/app.php';
    }

    function run(){
        $model = $this->getModel();
        $this->sync();
        $model->updateOrInsert('error', true);
    }

    protected function getModel(){
        $click = new \App\Models\Click();
        $click->referer = static::REFFERER;
        $click->ip = static::IP;
        $click->ua = static::USER_AGENT;
        $click->param1 = static::PARAM1;

        return $click;
    }

    protected function sync(){
        $this->waitFile();
        $startMicrotime = (int)file_get_contents(static::SYNC_FILE_PATH);
        usleep($startMicrotime - $this->getMicrotime());
    }

    protected function waitFile(){
        while(true){
            if(file_exists(static::SYNC_FILE_PATH)){
                return;
            }
            usleep(300000);
        }
    }

    protected function getMicrotime(){
        return microtime(1) * 1000000;
    }
}

(new CollisionTestThread)->run();

class MakeRequest{
    use \Laravel\Lumen\Testing\Concerns\MakesHttpRequests;
}