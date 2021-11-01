<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Http;
use Swoole\Http\Request;
use Swoole\Http\Response;
class App {
    public function __construct(Response $response, Request $request) {
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
