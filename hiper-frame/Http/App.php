<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Http;
use HP\Core;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
class App extends Core {

    public function run(){
        //实例化
        $http = new Server(CONFIG['HTTP_SERVER']['LISTEN_ADDRESS'], CONFIG['HTTP_SERVER']['PORT']);

        $http->set([
            'log_level'=> SWOOLE_LOG_ERROR,
            'log_file'=> $this->log_path.'/log.log',
            'pid_file'=> $this->temp_path.'/pid.pid',
            'stats_file' => $this->temp_path . '/stats.log',
            'worker_num'=> CONFIG['HTTP_SERVER']['PROCESS_COUNT'],
            'task_tmpdir'=> $this->temp_path,
            'max_coroutine'=> 100000,
            'hook_flags' => SWOOLE_HOOK_ALL, // 建议开启
        ]);

        // 接收请求
        $http->on('Request', function ($request, $response) {
            $this->onMessage($response, $request);
        });

        // 启动服务
        $http->start();
    }

    private function onMessage(Response $response, Request $request) {
        //路由分发: 模块=module 类=class 方法=function
        $path=trim($request->server['request_uri'],'/');
        $dot=strpos($path, '.');
        if($dot===false){
            $paths=explode('/',$path);
            if(isset($paths[0])){
                $module=ucwords($paths[0]);
            }else{
                $module='Index';
            }
            if(isset($paths[1])){
                $class_name=ucwords($paths[1]);
            }else{
                $class_name='Index';
            }
            $function = $paths[2] ?? 'index';
            $class='App\HttpController\\'.$module.'\\'.$class_name;
            if(class_exists($class)){
                $instance=new $class($response, $request);
                if(method_exists($instance, $function)){
                    $instance->$function();
                }else{
                    $instance=new Controller($response, $request);
                    $instance->writeJsonNoFound();
                }
            }else{
                $instance=new Controller($response, $request);
                $instance->writeJsonNoFound();
            }
        }else{
            $instance=new Controller($response, $request);
            $instance->writeFile($path);
        }
    }
}
