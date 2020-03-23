<?php
/**
 * Created by PhpStorm.
 * User: caoxiaobin
 */

namespace App\Helpers;

use Closure;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Psr\Log\LoggerInterface;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;

class Loge
{

    /**
     * The underlying logger implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $dispatcher;

    protected $is_formatter = false;
    /**
     * Create a new log writer instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @param  \Illuminate\Contracts\Events\Dispatcher|null  $dispatcher
     * @return void
     */
    public function __construct(LoggerInterface $logger, Dispatcher $dispatcher = null)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function emergency($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function alert($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function critical($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */

    public function error($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function warning($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log a notice to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function notice($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function info($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function debug($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Log a message to the logs.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function log($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Dynamically pass log calls into the writer.
     *
     * @param  string  $message 日志标题
     * @param  string/array/object  $context 日志内容
     * @param  string $file 指定日志存放的路径
     * @return void
     */
    public function write($message, $context = '', $file = '')
    {
        $this->writeLog(__FUNCTION__, $message, $context, $file);
    }

    /**
     * Write a message to the log.
     * @param $level 错误级别
     * @param $message 日志标题
     * @param $context 日志内容
     * @param $file 指定日志存放的路径
     */
    protected function writeLog($level, $message, $context, $file)
    {
        $filePath = $this->filePath($file);
        $content = $this->analysisContext($context);
        $level = $this->getLevel($level, $file);
        $this->fireLogEvent($level, $message = $this->formatMessage($message), $content->last());
        $result = $this->formatContext(debug_backtrace(), $level, $content->first(), $message);
//        $this->logger->{$level}($message);
        File::append($filePath, $result.PHP_EOL);
    }

    /**
     * @description 解析传入的内容
     * @param $context 可传入的类型为对象 数组（允许多维数组） 字符串
     * @return \Illuminate\Support\Collection
     * @auther caoxiaobin
     */
    private function analysisContext($context)
    {
        if (is_object($context)) {
            if (method_exists($context, 'getMessage')) {
                $EventContext = $context->getMessage();
                $ErrorLine = $context->getLine();
                $context = array();
                $context['Error'] =  $EventContext;
                $context['ErrorLine'] = $ErrorLine;
                $EventContext = array();
            } else {
                $context = $EventData = $context->toArray();
                $context = $this->newArr($context);
                $EventContext = $context;
            }
        } else if (is_array($context)) {
            $context = $this->analysisArr($context);
            $EventContext = $context;
        } else {
            if (!empty($context)) {
                $context = $this->analysisStr($context);
                $EventContext = $context;
            } else {
                $EventContext = [];
            }
        }
        return collect([$context, $EventContext]);
    }

    /**
     * @param $file
     * @param $level
     * @return mixed
     * @description 错误级别
     * @auther caoxiaobin
     */
    private function getLevel($level, $file)
    {
        if (!empty($file)) {
            $channelsFile = ucfirst($file);
            $filePath = config('logging.channels.'.$channelsFile.'.path');
            if (isset($filePath)) {
                $level == config('logging.channels.'.$channelsFile.'.level');
            }
        }
        return $level;
    }

    /**
     * @description 解析对象传入的数组
     * @param $context
     * @return array
     * @auther caoxiaobin
     */
    private function newArr($context)
    {
        $result = array();
        foreach ($context as $key => $value) {
            if (!is_array($value)) {
                $result = $this->analysisArr($context);
                break;
            }
            $newArr = array_divide($value);
            $firstValue = reset($newArr);
            $lastValue = end($newArr);
            foreach ($firstValue as $firstKey => $item) {
                $result[$item] = $lastValue[$firstKey];
            }
        }

        return $result;
    }

    /**
     * @description 解析数组
     * @param $arr
     * @param array $newArr
     * @return array
     * @auther caoxiaobin
     */
    private function analysisArr($arr, $newArr = [])
    {
        $resultArr = [];
        $childArr = collect();
        foreach ($arr as $key => $val) {
            if (!is_array($val)) {
                $resultArr[ucfirst($key)] = $val;
            } else {
                $childArr->push($val);
            }
        }
        $resultArr = array_merge($resultArr, $newArr);
        if ($childArr->isNotEmpty()) {
            return $this->analysisArr($childArr->collapse(), $resultArr);
        }
        return $resultArr;
    }

    /**
     * @param $str 字符串
     * @param array $result
     * @return array
     * @description 解析字符串中是否有json串
     * @auther caoxiaobin
     */
    public function analysisStr($str, $result = [])
    {
        $context = json_decode($str);
        if ($context == null) {
            return ['context' => $str];
        }
        $oldResult = $newArr = [];
        foreach ($context as $key => $item) {
            $key = str_replace('_', '', $key);
            if (!stristr($item, '{')) {
                $oldResult[ucfirst($key)] = $item;
            } else {
                $childArr = json_decode($item, true);
                $newArr = array_merge($newArr, $childArr);
            }
        }
        //info warning
        if (!empty($newArr)) {
            return $this->analysisStr(json_encode($newArr), $oldResult);
        }
        return array_merge($oldResult, $result);
    }

    /**
     * @description 创建目录，并返回文件存放地址
     * @param $file 指定存放的文件名
     * @return \Illuminate\Config\Repository|mixed|string
     * @auther caoxiaobin
     */
    private function filePath($file)
    {
        if (!empty($file)) {
            $channelsFile = ucfirst($file);
            $filePath = config('logging.channels.'.$channelsFile.'.path');
            if (isset($filePath)) {
                $default_dir = dirname($filePath);
                $fileName = substr(basename($filePath), 0, strlen(basename($filePath))-4).'.log';
                $dirName = dirname(app_path()).'/'.$default_dir;
                if ($dirName != $default_dir) {
                    $dirName = $default_dir;
                }
                $filePath = $dirName.'/'.$fileName;
            } else {
                $filePath = config('logging.channels.single.path');
                $dirName = dirname($filePath);
            }
        } else {
            $filePath = config('logging.channels.single.path');
            $dirName = dirname($filePath);
        }

        if (isset($_ENV['WWW_LOGDIR']) && !empty($_ENV['WWW_LOGDIR'])) {
            $filePath = $_ENV['WWW_LOGDIR'];
            $dirName = dirname($_ENV['WWW_LOGDIR']);
        }

        if(!is_writable($dirName) && is_dir($dirName)) {
            chmod($dirName,'0777');
        }
        return $filePath;
    }

    /**
     * Register a new callback handler for when a log event is triggered.
     *
     * @param  \Closure  $callback
     * @return void
     *
     * @throws \RuntimeException
     */
    public function listen(Closure $callback)
    {
        if (! isset($this->dispatcher)) {
            throw new RuntimeException('Events dispatcher has not been set.');
        }

        $this->dispatcher->listen(MessageLogged::class, $callback);
    }

    /**
     * Fires a log event.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array   $context
     * @return void
     */
    protected function fireLogEvent($level, $message, array $context = [])
    {
        // If the event dispatcher is set, we will pass along the parameters to the
        // log listeners. These are useful for building profilers or other tools
        // that aggregate all of the log messages for a given "request" cycle.
        if (isset($this->dispatcher)) {
            $this->dispatcher->dispatch(new MessageLogged($level, $message, $context));
        }
    }

    /**
     * Format the parameters for the logger.
     *
     * @param  mixed  $message
     * @return mixed
     */
    protected function formatMessage($message = '')
    {
        if (is_array($message)) {
            return var_export($message, true);
        } elseif ($message instanceof Jsonable) {
            return $message->toJson();
        } elseif ($message instanceof Arrayable) {
            return var_export($message->toArray(), true);
        }
        return $message;
    }

    /**
     * Get the underlying logger implementation.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return void
     */
    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dynamically proxy method calls to the underlying logger.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->logger->{$method}(...$parameters);
    }

    /**
     * @param array $error
     * @param string $level 错误级别
     * @param array $context 日志内容
     * @param string $message 日志标题
     * @return false|string
     * @description 监听错误文件
     * @auther caoxiaobin
     */
    private function formatContext($error, $level, $context, $message)
    {
        next($error);
        next($error);
        $innerResult = current($error);
        next($error);
        $outResult = current($error);
        if ($level == 'channel') {
            $level = 'debug';
        }
        $levels = strtoupper($level);

        $result = [
            'Applicaiton' => config('app.name'),
            'Environment' => config('app.model'),
            'Message' => $message,
            'Model'=>$this->subTit($outResult['class'], "\\"),
            'Class'=>$outResult['class'],
            'Function'=>$outResult['function'],
            'Line'=>$innerResult['line'],
            'Args'=> $outResult['args'],
            'File'=>isset($innerResult['file']) ? $innerResult['file'] : reset($error)['file'],
            'Level'=>$levels,
            'BeCalledFile'=>isset($outResult['file']) ? $outResult['file'] : reset($error)['file'],
            'BeCalledLine'=>isset($outResult['line']) ? $outResult['line'] : reset($error)['line'],
            'Time'=>date('Y-m-d H:i:s')
        ];

        $msg =  json_decode($message,true);
        $newMessage = [];
        foreach ($msg as $key => $value) {
            $newMessage['msg_' . $key] = $value;
        }
        $result=array_merge($result,$newMessage);

        if (!empty($context)) {
            $res = json_encode(array_merge($result, $context));
        } else {
            $res = json_encode($result);
        }
        return $res;
    }

    /**
     * 获取类名
     * @param $tit
     * @param $needle
     * @return bool|string
     */
    private function subTit($tit, $needle)
    {
        return substr($tit, strrpos($tit, $needle)+1);
    }
}