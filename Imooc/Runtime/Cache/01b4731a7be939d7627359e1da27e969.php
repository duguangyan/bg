<?php if (!defined('THINK_PATH')) exit();?><!doctype html><html lang="en"><head><meta charset="UTF-8" /><meta name='viewport' content="initial-scale=1.0;width=device-width" /><title>Document</title><script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script></head><body><a>aaaaaaaaaaaaaaaa</a><script type="text/javascript">
		wx.config({
		    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		    appId: 'wx40dd1ed6ab6087b2', // 必填，公众号的唯一标识
		    timestamp: '<<?php echo ($time); ?>>', // 必填，生成签名的时间戳
		    nonceStr: '<<?php echo ($noncestr); ?>>', // 必填，生成签名的随机串
		    signature: '<<?php echo ($signature); ?>>',// 必填，签名，见附录1
		    jsApiList: [
		    	'onMenuShareTimeline',
		    	'onMenuShareAppMessage'
		    ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});
		
		wx.ready(function(){
			wx.onMenuShareTimeline({
			    title: 'test1', // 分享标题
			    link: 'http://www.imooc.com', // 分享链接
			    imgUrl: 'http://static.mukewang.com/static/img/common/logo.png', // 分享图标
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
			wx.onMenuShareAppMessage({
			    title: 'test1', // 分享标题
			    desc: 'test imooc', // 分享描述
			    link: 'http://www.imooc.com', // 分享链接
			    imgUrl: 'http://static.mukewang.com/static/img/common/logo.png', // 分享图标
			    type: '', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
			
		});
		wx.error(function(res){

		    
		});
	</script></body></html>