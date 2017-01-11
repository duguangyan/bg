<?php
class IndexAction extends Action {

	public function __construct(){
		
	}

	public function index(){
		//获得参数 signature nonce token timestamp echostr
		$nonce     = $_GET['nonce'];
		$token     = 'duguangyan';
		$timestamp = $_GET['timestamp'];
		$echostr   = $_GET['echostr'];
		$signature = $_GET['signature'];
		//形成数组，然后按字典序排序
		$array = array();
		$array = array($nonce, $timestamp, $token);
		sort($array);
		//拼接成字符串,sha1加密 ，然后与signature进行校验
		$str = sha1( implode( $array ) );
		if( $str  == $signature && $echostr ){
			//第一次接入weixin api接口的时候
			echo  $echostr;
			exit;
		}else{
			$this->reponseMsg();
		}
	}
	// 接收事件推送并回复
	public function reponseMsg(){
		//1.获取到微信推送过来post数据（xml格式）
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		//2.处理消息类型，并设置回复类型和内容
		/*<xml>
		<ToUserName><![CDATA[toUser]]></ToUserName>
		<FromUserName><![CDATA[FromUser]]></FromUserName>
		<CreateTime>123456789</CreateTime>
		<MsgType><![CDATA[event]]></MsgType>
		<Event><![CDATA[subscribe]]></Event>
		</xml>*/
		$postObj = simplexml_load_string( $postArr );
		//$postObj->ToUserName = '';
		//$postObj->FromUserName = '';
		//$postObj->CreateTime = '';
		//$postObj->MsgType = '';
		//$postObj->Event = '';
		// gh_e79a177814ed
		//判断该数据包是否是订阅的事件推送
		if( strtolower( $postObj->MsgType) == 'event'){
			//如果是关注 subscribe 事件
			if( strtolower($postObj->Event == 'subscribe') ){
				//回复用户消息(纯文本格式)	
				$arr = array(
					array(
						'title'=>'imooc',
						'description'=>"杜光焱 is very cool",
						'picUrl'=>'http://b62.photo.store.qq.com/psu?/19e3d275-f866-4989-922b-2c717ce6eeca/OJtWNHIfK5hj5iUhXZhugBiEXfL6jQPug0Nck1nSmW8!/b/YRQZ.SSCjAAAYoqn*SQGigAA&bo=ngL2AQAAAAABBEg!&rf=viewer_4',
						'url'=>'http://www.du-u.top',
					)
				);	
				$indexModel = new IndexModel;
				$indexModel->responseSubscribe($postObj,$arr);
			}
			//如果是浏览 重扫二维码
			if(strtolower($postObj->Event)== 'scan'){
				if($postObj->EventKey == 2000){
					//如果是临时扫二维码进来
					$tmp = '临时二维码欢迎你!';
				}
				if($postObj->EventKey == 3000){
					//如果是永久 二维码进来
					$tmp = '永久二维码欢迎你!';
				}
				$arr = array(
					array(
						'title'=>$tmp,
						'description'=>"杜光焱 is very cool",
						'picUrl'=>'http://b62.photo.store.qq.com/psu?/19e3d275-f866-4989-922b-2c717ce6eeca/OJtWNHIfK5hj5iUhXZhugBiEXfL6jQPug0Nck1nSmW8!/b/YRQZ.SSCjAAAYoqn*SQGigAA&bo=ngL2AQAAAAABBEg!&rf=viewer_4',
						'url'=>'http://www.du-u.top',
					)
				);		
				$indexModel = new IndexModel;
				$indexModel->responseSubscribe($postObj,$arr);
			}
		}
		if(strtolower($postObj->Event)=='click'){
			//如果是自定义菜单的event->click事件
			if(strtolower($postObj->EventKey)=='v1'){
				$content = "这是今日歌曲的事件推送";
			}
			if(strtolower($postObj->EventKey)=='v11'){
				$content = "这是v11事件推送";
			}
			$indexModel = new IndexModel;
			$indexModel->responseText($postObj,$content);
				
		}
		if(strtolower($postObj->Event)=='view'){
			//如果是自定义菜单的event->click事件
			$content = '跳转链接是'.$postObj->EventKey;
			$indexModel = new IndexModel;
			$indexModel->responseText($postObj,$content);
				
		}
		//用户回复immoc 公众号返回 imooc is very good
//		if(strtolower($postObj->MsgType)=='text'){
//			if($postObj->Content == 'imooc'){
//				$template = "<xml>
//							<ToUserName><![CDATA[%s]]></ToUserName>
//							<FromUserName><![CDATA[%s]]></FromUserName>
//							<CreateTime>%s</CreateTime>
//							<MsgType><![CDATA[%s]]></MsgType>
//							<Content><![CDATA[%s]]></Content>
//							</xml>";
//							$fromUser = $postObj->ToUserName;
//							$toUser   = $postObj->FromUserName;
//							$time     = time();
//							$content  = 'imooc is very good';
//							$msgType  = 'text';
//							echo sprintf($template, $toUser,$fromUser,$time,$msgType,$content);
//			}
//		}

		//用户回复不同内容 公众号返回不同的内容
		/*if(strtolower($postObj->MsgType) == 'text'){
			switch( trim($postObj->Content) ){
				case 1:
					$content = '您输入的数字是1';
				break;
				case 2:
					$content = '您输入的数字是2';
				break;
				case 3:
					$content = '您输入的数字是3';
				break;
				case 4:
					$content = "<a href='http://www.imooc.com'>慕课</a>";
				break;
				case '英文':
					$content = 'imooc is ok';
				break;
				default:
  					$content = '你查找的资料没有，请重新输入';
			}	
				$template = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
							</xml>";
				$fromUser = $postObj->ToUserName;
				$toUser   = $postObj->FromUserName; 
				$time     = time();
				// $content  = '18723180099';
				$msgType  = 'text';
				echo sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
			
		}
	}
*/
		//用户发送tuwen2关键字的时候，回复一个多图文
		if( strtolower($postObj->MsgType) == 'text' && trim($postObj->Content)=='tuwen2' ){
			//数据
			$arr = array(
				array(
					'title'=>'imooc',
					'description'=>"imooc is very cool",
					'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
					'url'=>'http://www.imooc.com',
				),
				array(
					'title'=>'hao123',
					'description'=>"hao123 is very cool",
					'picUrl'=>'https://www.baidu.com/img/bdlogo.png',
					'url'=>'http://www.hao123.com',
				),
				array(
					'title'=>'qq',
					'description'=>"qq is very cool",
					'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
					'url'=>'http://www.qq.com',
				),
			);	
			//实例化模型
			$indexModel = new IndexModel;
			$indexModel->responseNews($postObj,$arr);
			//注意：进行多图文发送时，子图文个数不能超过10个
		}else{
			switch( trim($postObj->Content) ){
				case 1:
					$content = '您输入的数字是1';
				break;
				case 2:
					$content = '您输入的数字是2';
				break;
				case 3:
					$content = '您输入的数字是3';
				break;
				case 4:
					$content = "<a href='http://www.imooc.com'>慕课</a>";
				break;
				case 5:
					$content = '微信is very good !';
				break;
				case '英文':
					$content = 'imooc is ok';
				break;
				default:
					$content = '没有找到相关信息 !';
				break;
			}	
				$indexModel = new IndexModel;
				$indexModel->responseText($postObj,$content);
			
		}//if end
	}//reponseMsg end

//	function http_curl(){
//		//获取imooc
//		//1.初始化curl
//		$ch = curl_init();
//		$url = 'http://www.baidu.com';
//		//2.设置curl的参数
//		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		//3.采集
//		$output = curl_exec($ch);
//		//4.关闭
//		curl_close($ch);
//		var_dump($output);
//	}
	/*
	 *$url 接口url string
	 *$type 请求类型 string
	 * $res 返回数据类型 string 
	 * $arr post请求参数 string
	 *  
	 */
	function http_curl($url,$type='get',$res='json',$arr=''){
		//1.初始化curl
		$ch = curl_init();
		//2.设置curl的参数
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($type=='post'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		}
		//3.采集
		$output = curl_exec($ch);
		//4.关闭
		curl_close($ch);
		if($res=='json'){
			return json_decode($output,true);
		}
		if (curl_errno($ch)) {  
          echo curl_error($ch);  
         }  
		
	}
	
//
//	function getWxAccessToken(){
//		//1.请求url地址
////		$appid = 'wx40dd1ed6ab6087b2';   
////		$appsecret =  'f6bd4898eb26049e50e1083850e179cf';
//		$appid = 'wxd4e9b5e2e94c58ff';   //自定义菜单的appid
//		$appsecret =  '1501a2d7cf9c9f4570416181a67d19f7';
//		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//		//2初始化
//		$ch = curl_init();
//		//3.设置参数
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
//      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
//		curl_setopt($ch , CURLOPT_URL, $url);
//		curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
//		//4.调用接口 
//		$res = curl_exec($ch);
//		//5.关闭curl
//		curl_close( $ch );
//		if( curl_errno($ch) ){
//			var_dump( curl_error($ch) );
//		}
//		$arr = json_decode($res, true);
//		dump( $arr );
//		//echo $arr['access_token'];
//	}
	
	function getWxServerIp(){
		$accessToken = "MJgcRrWDwsdNdCL-CkC50nGaWY-qsNorw9VIjgtbnfmJYC_d8YAsmhejKFAC_TlfO7GoZxAnD5zEuF-P2_AwQgfoVbF_jvD88SIL6UjJVB7oVJfRFBEi2LvV6C2qgEyQOQAbAGAZBL";
		$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 跳过证书检查  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$res = curl_exec($ch);
		curl_close($ch);
		if(curl_errno($ch)){
			var_dump(curl_error($ch));
		}
		$arr = json_decode($res,true);
		echo "<pre>";
		var_dump( $arr );
		echo "</pre>";
	}
	//返回自定义菜单access_token  session 解决方法 msql
	public function getWxAccessToken(){
		//将access_token存在session/cookie中
		//$_SESSION['expire_time'] = 0;
		if($_SESSION['access_token'] && $_SESSION['expire_time']>time()){
			//如果acccess_token在session并没有过期
			//echo $_SESSION['access_token'];
			return $_SESSION['access_token'];
		}else{
			//如果acccess_token在session不存在或者已过期，重新获取access_token 
			$appid = 'wxd4e9b5e2e94c58ff';   //自定义菜单的appid
			$appsecret =  '1501a2d7cf9c9f4570416181a67d19f7';
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
			$res= $this->http_curl($url,'get','json');
			$access_token = $res['access_token'];
			//将重新获取到的access_token存到session
			$_SESSION['access_token'] = $access_token;
			$_SESSION['expire_time'] = time()+7000;
			//echo $access_token;
			return $access_token;
		}
	}
	
	//创建微信菜单
	public function definedItem(){
		
		//目前微信接口的调用方式都是通过curl post/get
		header('Content-Type:text/html; charset=utf-8');
		echo $access_token = $this->getWxAccessToken();
		echo '<hr />';
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
		$data = ' {  
             "button":[  
             {    
                  "type":"click",  
                  "name":"今日歌曲",  
                  "key":"v1"  
              },  
              {  
                    "name":"杜光焱",  
                   "sub_button":[  
                   {      
                       "type":"view",  
                       "name":"姓名",  
                       "url":"http://www.du-u.top/"  
                    },  
                    {  
                       "type":"view",  
                       "name":"地址",  
                       "url":"http://www.qq.com/"  
                    },  
                    {  
                       "type":"click",  
                       "name":"赞一下我们",  
                       "key":"V11"  
                    }]  
                
              },  
              {  
                   "name":"菜单",  
                   "sub_button":[  
                   {      
                       "type":"view",  
                       "name":"搜索",  
                       "url":"http://www.du-u.top/"  
                    },  
                    {  
                       "type":"view",  
                       "name":"视频",  
                       "url":"http://www.qq.com/"  
                    },  
                    {  
                       "type":"click",  
                       "name":"赞一下我们",  
                       "key":"v12"  
                    }]  
               }]  
         	}';  
  
         $ch = curl_init();  
         curl_setopt($ch, CURLOPT_URL, $url);  
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
         curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
         $tmpInfo = curl_exec($ch);  
         if (curl_errno($ch)) {  
          echo curl_error($ch);  
         }  
          
         curl_close($ch);  
            
        echo $tmpInfo;   
	}
	//群发接口
	function sendMsgAll(){
		//1.获取全局accsess_token
		echo $access_token = $this->getWxAccessToken();
		echo '<hr>';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token;
		//2.组装群发接口数据
//		$array = array(
//			'touser'=>'ogNN4w9CfnUHf7LXZ6jdpAtNGFYY',//微信用户的openid
//			'text'=>array(
//				'content'=>'imooc is very happy !' //文本内容
//			),
//			'msgtype'=>'text'   //消息类型
//		
//		);
		//3.将array->json
//		$postJson = json_encode( $array );
		//4.调用curl
		$data = '{     
		    "touser":"ogNN4w9CfnUHf7LXZ6jdpAtNGFYY",
		    "text":{           
		           "content":"imooc is very happy !"            
		           },     
		    "msgtype":"text"
		}'; 
		
		
		 $ch = curl_init();  
         curl_setopt($ch, CURLOPT_URL, $url);  
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
         curl_setopt($ch, CURLOPT_AUTOREFERER, 1);  
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
         $tmpInfo = curl_exec($ch);  
         if (curl_errno($ch)) {  
          echo curl_error($ch);  
         }  
          
         curl_close($ch);  
            
         echo $tmpInfo;  
	}
	
	//基础网页授权
	//获取用户的openid
	function getBaseInfo(){
		//1.获取code
		$appid = 'wxd4e9b5e2e94c58ff';
		$redirect_uri =urlencode('http://www.du-u.top/bg/imooc.php/Index/getUserOpenId');
		$url ='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
		header('location:'.$url);
	}
	
	function getUserOpenId(){
		//2.获取大欧网页授权access_token
		$appid = 'wxd4e9b5e2e94c58ff';
		$appsecret = '1501a2d7cf9c9f4570416181a67d19f7';
		$code = $_GET['code'];
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		//3.拉取用户的openid
		$res = $this->http_curl($url,'get');
		dump($res);
		$openid = $res['openid'];
		//time();
		//1,2,3
		//页面 index.tpl
		//$this->diaplay('index.tpl');
	}
	//获取用户详细信息
	function getUserDetail(){
		//1.获取code
		$appid = 'wxd4e9b5e2e94c58ff';
		$redirect_uri =urlencode('http://www.du-u.top/bg/imooc.php/Index/getUserInfo');
		$url ='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
		header('location:'.$url);
		
	}
	
	function getUserInfo(){
		//2.获取大欧网页授权access_token
		$appid = 'wxd4e9b5e2e94c58ff';
		$appsecret = '1501a2d7cf9c9f4570416181a67d19f7';
		$code = $_GET['code'];
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$res = $this->http_curl($url,'get');
		$openid = $res['openid'];
		$access_token = $res['access_token'];
		//3.拉取用户的详细信息
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$res = $this->http_curl($url);
		dump($res);
	}
	
	

	//模板消息
	function sendTemplateMsg(){
		//1.获取access_token
		echo $access_token = $this->getWxAccessToken();
		$url ='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		//2.组装数组
		$array =array (
  'touser' => 'ogNN4w9CfnUHf7LXZ6jdpAtNGFYY',
  'template_id' => 'hStKDyhJu7iFKAVh2rBG_z1bXfC7ahUEePjetnWYH_Q',
  'url' => 'http://www.du-u.top',
  'data' =>array (
		    'name' => 
		    array (
		      'value' => '      恭喜你购买成功！',
		      'color' => '#173177',
		    ),
		    'money' => 
		    array (
		      'value' => '100',
		      'color' => '#173177',
		    ),
		    'date' => 
		    array (
		      'value' => date('Y-m-d H-i-s'),
		      'color' => '#173177',
		    ),
		  ),
		); 
		//3.将数组—>json
		$postJson = json_encode($array);
		
		//4.调用curl函数
		$res = $this->http_curl($url,'post','json',$postJson);
		dump($res);  

	}  
	
	//实现临时二维码
	function getTimeQrCode(){
		//1.获取ticket票据
		//全局票据access_token 网页授权access_token jsapi_ticket
		$access_token = $this->getWxAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
		//{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		
		$postArr = array (
				  'expire_seconds' => '604800',
				  'action_name' => 'QR_SCENE',
				  'action_info' => 
				  array (
				    'scene' => 
				    array (
				      'scene_id' => '2000',
				    ),
				  ),
				);
		$postJson = json_encode($postArr);
		$res = $this->http_curl($url,'post','json',$postJson);
		//dump($res);
		$ticket = $res['ticket'];
		//2.通过使用ticket获取二维码图片
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		echo '临时二维码';
		echo '<br>';
		echo "<img src='".$url."' />";
	}
	
	//实现永久二维码
	function getForeverQrCode(){
		//1.获取ticket票据
		//全局票据access_token 网页授权access_token jsapi_ticket
		$access_token = $this->getWxAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
		//{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		
		$postArr = array (
				  'action_name' => 'QR_LIMIT_SCENE',
				  'action_info' => 
				  array (
				    'scene' => 
				    array (
				      'scene_id' => '3000',
				    ),
				  ),
				);
		$postJson = json_encode($postArr);
		$res = $this->http_curl($url,'post','json',$postJson);
		//dump($res);
		$ticket = $res['ticket'];
		//2.通过使用ticket获取二维码图片
		$url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
		echo '永久二维码';
		echo '<br>';
		echo "<img src='".$url."' />";
	}
	//获取jsapi_ticket票据方法
	function getJsApiTicket(){
		//如果session中保存有效的jsapi_ticket
		if($_SESSION['jsapi_ticket_expire_time']>time() && $_SESSION['jsapi_ticket']){
		    $jsapi_ticket = $_SESSION['jsapi_ticket'];
		}else{
			$access_token = $this->getWxAccessToken();
			$url ='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
			$res = $this->http_curl($url);
			$jsapi_ticket = $res['ticket'];
			$_SESSION['jsapi_ticket'] = $jsapi_ticket;
			$_SESSION['jsapi_ticket_expire_time'] = time()+7000;
		}
		return $jsapi_ticket;
	}
	//获取16位随机码
	function getRandCode($num=16){
		$array = array(
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'1','2','3','4','5','6','7','8','9','0'
		);
		$tmpstr = '';
		$max = count($array);
		for($i=0;$i<$num;$i++){
			$key = rand(0,$max-1);
			$tmpstr .= $array[$key];
		}
		return $tmpstr;
	}
	
	//微信分享朋友圈
	function shareWx(){
		//1.获取jsapi_ticket票据
		echo $jsapi_ticket = $this->getJsApiTicket();
		echo '<hr>';
		echo $timestamp = time();
		echo '<hr>';
	    echo $noncestr  = $this->getRandCode();
	    echo '<hr>';
		echo $url = 'http://www.du-u.top/bg/imooc.php/Index/shareWx';
		//2.获取signature
		echo '<hr>';
		echo $signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
						  
		echo '<hr>';
		echo $signature = sha1( $signature );
		$this->assign('name','杜光焱');
		$this->assign('timestamp',$timestamp);
		$this->assign('noncestr',$noncestr);
		$this->assign('signature',$signature);
		$this->display('share');
	}
	
	

	
} //class end

