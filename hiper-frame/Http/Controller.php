<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Http;
use Swoole\Http\Request;
use Swoole\Http\Response;
class Controller {
    protected $response;
    protected $request;

    public function __construct(Response $response, Request $request) {
        $this->response=$response;
        $this->request=$request;
    }

    public function writeJson($code , $result, $msg) {
        $body=json_encode([
            "code" => $code,
            "result" => !empty($result)?$result:['empty'=>1],
            "msg" => $msg
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->response->setStatusCode(200);
        $this->response->header('Content-Type', 'text/json; charset=utf-8');
        $this->response->header('Server', 'InfobirdCloud');
        $this->response->end($body);
    }

    public function writeFile($file) {
        $file_abs=PUBLIC_ROOT.'/'.$file;
        if (!\is_file($file_abs)) {
            $this->response->setStatusCode(404);
            $this->response->header('Content-Type', 'text/html; charset=utf-8');
            $this->response->header('Server', 'InfobirdCloud');
            $this->response->end('<h3>404 Not Found</h3>');
        }else{
            $mime_type = mime_content_type($file_abs);
            $this->response->setStatusCode(200);
            $this->response->header('Content-Type', $mime_type.'; charset=utf-8');
            $this->response->header('Server', 'InfobirdCloud');
            $this->response->sendfile($file_abs);
        }
    }

    public function writeJsonNoFound() {
        $this->writeJson(400, [
            "uri"=>$this->request->header['host'].$this->request->server['request_uri'],
            "method"=>$this->request->getMethod(),
            "param"=>$this->request->get?:$this->request->post?:['empty'=>1]
        ], 'the api uri not found');
    }
}
