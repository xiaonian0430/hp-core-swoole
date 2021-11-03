<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Gateway;
use HP\Core;
use HP\Swoole\Gateway;
class App extends Core {

    public function run(){
        //实例化
        $gateway = new Gateway();

        // 设置pid文件
        $gateway->pid_file = $this->temp_path . '/pid.pid';

        // 设置服务端参数 参考:http://wiki.swoole.com/#/server/setting
        $gateway->set([
            'log_level'=> SWOOLE_LOG_ERROR,
            'log_file'=> $this->log_path.'/log.log',
            'pid_file'=> $this->temp_path.'/pid.pid',
            'stats_file' => $this->temp_path . '/stats.log',
            'worker_num'=> CONFIG['HTTP_SERVER']['PROCESS_COUNT'],
            'task_tmpdir'=> $this->temp_path,
            'max_coroutine'=> 100000
        ]);

        // 设置注册中心连接参数
        $gateway->register_host = CONFIG['REGISTER']['LAN_IP'];
        $gateway->register_port = CONFIG['REGISTER']['LAN_PORT'];

        // 设置内部连接参数
        $gateway->lan_host = CONFIG['GATEWAY']['LAN_IP'];
        $gateway->lan_port = CONFIG['GATEWAY']['LAN_START_PORT'];

        $gateway->listen(CONFIG['GATEWAY']['LISTEN_ADDRESS'], CONFIG['GATEWAY']['PORT'], [
            'open_websocket_protocol' => true,
            'open_websocket_close_frame' => true,
        ]);

        //启动
        $gateway->start();
    }
}
