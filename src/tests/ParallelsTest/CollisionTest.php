<?php
namespace Tests\ParallelsTest;
use App\Models\Click;
use Tests\TestCase;

class CollisionTest extends TestCase
{
    const SYNC_FILE_PATH = __DIR__ . '/microtime_start.sync';

    const COMMAND = 'php ' . __DIR__ . '/parallels_request.php > /dev/null &';

    const COUNT_THREADS = 60;

    const COUNT_TRY = 3;

    function testNotCollisionError(){
        for ($i = 0; $i < static::COUNT_TRY; $i++){
            $this->collisionTest();
            Click::query()->delete();
        }
    }

    function collisionTest(){
        $this->deleteSyncFile();
        for($i = 0; $i < static::COUNT_THREADS; $i++){
            exec(static::COMMAND);
        }
        $this->createSync(10);

        $this->waitProcess();

        $this->deleteSyncFile();

        $this->assertEquals(Click::query()->count(), 1);

        $click = Click::query()->first();

        /**
         * @var Click $click
         */
        $this->assertEquals($click->error, static::COUNT_THREADS - 1);
    }

    protected function waitProcess(){
        $i = 0;
        while($i < 1000){ //Add timeout
            $i++;
            $command = 'ps -C "'.static::COMMAND.'" --no-headers';
            $processes = [];
            exec($command, $processes);
            if(count($processes) > 1){
                sleep(1);
            }
            else{
                break;
            }
        }
    }

    protected function createSync($waitSecond){
        $startTime = (microtime(1) + $waitSecond) * 1000000;
        file_put_contents(static::SYNC_FILE_PATH, $startTime);
    }

    protected function deleteSyncFile(){
        if(file_exists(static::SYNC_FILE_PATH)){
            unlink(static::SYNC_FILE_PATH);
        }
    }

    function tearDown()
    {
        $this->deleteSyncFile();
        parent::tearDown();
    }
}