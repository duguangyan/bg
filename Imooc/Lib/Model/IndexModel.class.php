<?php
	class IndexModel{
		public function responseNews($postObj,$arr){
			$toUser   = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($arr)."</ArticleCount>
						<Articles>";
			foreach($arr as $k=>$v){
				$template .="<item>
							<Title><![CDATA[".$v['title']."]]></Title> 
							<Description><![CDATA[".$v['description']."]]></Description>
							<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
							<Url><![CDATA[".$v['url']."]]></Url>
							</item>";
			}
			
			$template .="</Articles>
						</xml> ";
			echo sprintf($template, $toUser, $fromUser, time(), 'news');
		}
		
		//恢复单文本
		public function responseText($postObj,$content){
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
		
		public function responseSubscribe($postObj,$arr){
				$this->responseNews($postObj, $arr);
		}
	}
	