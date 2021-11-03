<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Register;
use HP\Core;
use HP\Swoole\Register;
class App extends Core {

    public function run(){
        //实例化
        //初始化注册中心
        $register = new Register(CONFIG['REGISTER']['LISTEN_ADDRESS'], CONFIG['REGISTER']['PORT']);

        // 设置pid文件
        $register->pid_file = $this->temp_path. '/pid.pid';

        // 设置服务端参数 参考:http://wiki.swoole.com/#/server/setting
        $register->set([
            'log_level'=> SWOOLE_LOG_ERROR,
            'log_file'=> $this->log_path.'/log.log',
            'pid_file'=> $this->temp_path.'/pid.pid',
            'stats_file' => $this->temp_path . '/stats.log',
            'worker_num'=> CONFIG['HTTP_SERVER']['PROCESS_COUNT'],
            'task_tmpdir'=> $this->temp_path,
            'max_coroutine'=> 100000
        ]);

        //启动
        $register->start();
    }
}
