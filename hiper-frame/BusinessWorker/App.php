<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\BusinessWorker;
use HP\Core;
use HP\Swoole\BusinessWorker;
class App extends Core {

    public function run(){
        //实例化
        $business = new BusinessWorker();

        // 设置pid文件
        $business->pid_file = $this->temp_path . '/pid.pid';

        $business->set([
            'worker_file'=> SERVER_ROOT.'/Events.php'
        ]);

        // 设置服务端参数 参考:http://wiki.swoole.com/#/server/setting
        $business->set([
            'log_level'=> SWOOLE_LOG_ERROR,
            'log_file'=> $this->log_path.'/log.log',
            'pid_file'=> $this->temp_path.'/pid.pid',
            'stats_file' => $this->temp_path . '/stats.log',
            'worker_num'=> CONFIG['HTTP_SERVER']['PROCESS_COUNT'],
            'task_tmpdir'=> $this->temp_path,
            'max_coroutine'=> 100000,
            'hook_flags' => SWOOLE_HOOK_ALL, // 建议开启
        ]);

        // 设置注册中心连接参数
        $business->register_host = CONFIG['REGISTER']['LAN_IP'];
        $business->register_port = CONFIG['REGISTER']['LAN_PORT'];

        //启动
        $business->start();
    }
}
