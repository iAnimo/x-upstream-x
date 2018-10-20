<?php
@header('Content-type: text/html;charset=UTF-8');
//设置时区
date_default_timezone_set("Asia/Shanghai");
//定义TOKEN常量，这里的"weixin"就是在公众号里配置的TOKEN
 
require_once("Utils.php");
//打印请求的URL查询字符串到query.xml
Utils::traceHttp();
 
$wechatObj = new wechatCallBackapiTest();
$wechatObj->responseMsg();
 
class wechatCallBackapiTest
{
    public function responseMsg()
    {
        //获取post过来的数据，它一个XML格式的数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //将数据打印到log.xml
        Utils::logger($postStr);
        if (!empty($postStr)) {
            //将XML数据解析为一个对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            //消息类型分离
            switch($RX_TYPE)
            {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    $result = "";
                    break;
            }
            Utils::logger($result, '公众号');
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
 
    private function receiveText($object)
    {
        $appid = "wx07fff9c79a410b69";
        $redirect_uri = urlencode("http://weiweiyi.duapp.com/oauth/oauth2.php");
 
        $keyword = trim($object->Content);
        if(strstr($keyword, "base")){
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=".
                "$redirect_uri&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
            $content = "用户授权snsapi_base实现:<a href='$url'>单击这里体验OAuth授权</a>";
        }else if (strstr($keyword, "userinfo")){
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=".
                "$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
            $content = "用户授权snsapi_userInfo实现:<a href='$url'>单击这里体验OAuth授权</a>";
        }else{
            $content = "";
        }
        $result = $this->transmitText($object, $content);
        return $result;
    }
    /**
     * 回复文本消息
     */
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime><![CDATA[%s]]></CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
}
--------------------- 
作者：zc的救赎 
来源：CSDN 
原文：https://blog.csdn.net/qq_28506819/article/details/78008390 
版权声明：本文为博主原创文章，转载请附上博文链接！
