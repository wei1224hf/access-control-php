/**
 * 一些额外的
 * 与系统业务逻辑无关的小函数,将被包含在这个文件内
 * 
 * @author wei1224hf@gmail.com
 * @version 201201
 * */

/**
 * JS实现的MD5加密,单向加密,无法解密
 * */
function MD5 (string) {

	function RotateLeft(lValue, iShiftBits) {
	   return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
	}

	function AddUnsigned(lX,lY) {
	   var lX4,lY4,lX8,lY8,lResult;
	   lX8 = (lX & 0x80000000);
	   lY8 = (lY & 0x80000000);
	   lX4 = (lX & 0x40000000);
	   lY4 = (lY & 0x40000000);
	   lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
	   if (lX4 & lY4) {
		return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
	   }
	   if (lX4 | lY4) {
		if (lResult & 0x40000000) {
		 return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
		} else {
		 return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
		}
	   } else {
		return (lResult ^ lX8 ^ lY8);
	   }
	   }

	   function F(x,y,z) { return (x & y) | ((~x) & z); }
	   function G(x,y,z) { return (x & z) | (y & (~z)); }
	   function H(x,y,z) { return (x ^ y ^ z); }
	function I(x,y,z) { return (y ^ (x | (~z))); }

	function FF(a,b,c,d,x,s,ac) {
	   a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
	   return AddUnsigned(RotateLeft(a, s), b);
	};

	function GG(a,b,c,d,x,s,ac) {
	   a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
	   return AddUnsigned(RotateLeft(a, s), b);
	};

	function HH(a,b,c,d,x,s,ac) {
	   a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
	   return AddUnsigned(RotateLeft(a, s), b);
	};

	function II(a,b,c,d,x,s,ac) {
	   a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
	   return AddUnsigned(RotateLeft(a, s), b);
	};

	function ConvertToWordArray(string) {
	   var lWordCount;
	   var lMessageLength = string.length;
	   var lNumberOfWords_temp1=lMessageLength + 8;
	   var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
	   var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
	   var lWordArray=Array(lNumberOfWords-1);
	   var lBytePosition = 0;
	   var lByteCount = 0;
	   while ( lByteCount < lMessageLength ) {
		lWordCount = (lByteCount-(lByteCount % 4))/4;
		lBytePosition = (lByteCount % 4)*8;
		lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
		lByteCount++;
	   }
	   lWordCount = (lByteCount-(lByteCount % 4))/4;
	   lBytePosition = (lByteCount % 4)*8;
	   lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
	   lWordArray[lNumberOfWords-2] = lMessageLength<<3;
	   lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
	   return lWordArray;
	};

	function WordToHex(lValue) {
	   var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
	   for (lCount = 0;lCount<=3;lCount++) {
		lByte = (lValue>>>(lCount*8)) & 255;
		WordToHexValue_temp = "0" + lByte.toString(16);
		WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
	   }
	   return WordToHexValue;
	};

	function Utf8Encode(string) {

	   var utftext = "";

	   for (var n = 0; n < string.length; n++) {

		var c = string.charCodeAt(n);

		if (c < 128) {
		 utftext += String.fromCharCode(c);
		}
		else if((c > 127) && (c < 2048)) {
		 utftext += String.fromCharCode((c >> 6) | 192);
		 utftext += String.fromCharCode((c & 63) | 128);
		}
		else {
		 utftext += String.fromCharCode((c >> 12) | 224);
		 utftext += String.fromCharCode(((c >> 6) & 63) | 128);
		 utftext += String.fromCharCode((c & 63) | 128);
		}

	   }

	   return utftext;
	};

	var x=Array();
	var k,AA,BB,CC,DD,a,b,c,d;
	var S11=7, S12=12, S13=17, S14=22;
	var S21=5, S22=9 , S23=14, S24=20;
	var S31=4, S32=11, S33=16, S34=23;
	var S41=6, S42=10, S43=15, S44=21;

	string = Utf8Encode(string);

	x = ConvertToWordArray(string);

	a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;

	for (k=0;k<x.length;k+=16) {
	   AA=a; BB=b; CC=c; DD=d;
	   a=FF(a,b,c,d,x[k+0], S11,0xD76AA478);
	   d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
	   c=FF(c,d,a,b,x[k+2], S13,0x242070DB);
	   b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
	   a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
	   d=FF(d,a,b,c,x[k+5], S12,0x4787C62A);
	   c=FF(c,d,a,b,x[k+6], S13,0xA8304613);
	   b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
	   a=FF(a,b,c,d,x[k+8], S11,0x698098D8);
	   d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
	   c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
	   b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
	   a=FF(a,b,c,d,x[k+12],S11,0x6B901122);
	   d=FF(d,a,b,c,x[k+13],S12,0xFD987193);
	   c=FF(c,d,a,b,x[k+14],S13,0xA679438E);
	   b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
	   a=GG(a,b,c,d,x[k+1], S21,0xF61E2562);
	   d=GG(d,a,b,c,x[k+6], S22,0xC040B340);
	   c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);
	   b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
	   a=GG(a,b,c,d,x[k+5], S21,0xD62F105D);
	   d=GG(d,a,b,c,x[k+10],S22,0x2441453);
	   c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
	   b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
	   a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
	   d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);
	   c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
	   b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
	   a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
	   d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
	   c=GG(c,d,a,b,x[k+7], S23,0x676F02D9);
	   b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
	   a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
	   d=HH(d,a,b,c,x[k+8], S32,0x8771F681);
	   c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
	   b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
	   a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
	   d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
	   c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
	   b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
	   a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
	   d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
	   c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
	   b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
	   a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
	   d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
	   c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
	   b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
	   a=II(a,b,c,d,x[k+0], S41,0xF4292244);
	   d=II(d,a,b,c,x[k+7], S42,0x432AFF97);
	   c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);
	   b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
	   a=II(a,b,c,d,x[k+12],S41,0x655B59C3);
	   d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
	   c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
	   b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
	   a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
	   d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
	   c=II(c,d,a,b,x[k+6], S43,0xA3014314);
	   b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
	   a=II(a,b,c,d,x[k+4], S41,0xF7537E82);
	   d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);
	   c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
	   b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
	   a=AddUnsigned(a,AA);
	   b=AddUnsigned(b,BB);
	   c=AddUnsigned(c,CC);
	   d=AddUnsigned(d,DD);
	}

	var temp = WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);

	return temp.toLowerCase();
} 

/**
 * 以JS的方式,获得 URL 地址中的 GET 参数
 * */
function getParameter(argname, url)
{
	if( typeof(url)=="undefined") url = window.location.toString();
	var arrStr = url.substring(url.indexOf("?")+1).split("&");
	for(var i =0;i<arrStr.length;i++)
	{
	   var loc = arrStr[i].split("=");
	   if(loc[0]==argname)return loc[1];
	}
	return "";
}

/**
 * 对 COOKIE 的读写操作
 * */
function SetCookie(name,value,Hours)// 两个参数，一个是cookie的名子，一个是值,一个是时间(小时)
{
	if(Hours!=undefined){// 默认的,保存半天,12小时
		Hours = 2 ;
	}	
    var exp  = new Date();    // new Date("December 31, 9998");
    exp.setTime(exp.getTime() + Hours*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
function getCookie(name)// 取cookies函数
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null){ 
		return unescape(arr[2]);
	}else{ 
		return null;
	}

}
function delCookie(name)// 删除cookie
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

/**
 * 异步导入JS文件跟CSS文件
 * */
function importJS(src,oCallback) {     
    /*
	 * fpath = fpath.replace(/\./g,'\/'); document.write('<script
	 * type="text/javascript" src="'+ fpath + '.js"></script>');
	 */        
    var headerDom = document.getElementsByTagName('head').item(0);     
    var jsDom = document.createElement('script');     
    jsDom.type = 'text/javascript';     
    jsDom.src = src;     
   
    if(oCallback === undefined){
    	
    }else{
	    jsDom.onload = oCallback;
	 // IE 6 & 7
	    jsDom.onreadystatechange = function() {
	    	if (this.readyState == 'complete') {
	    		oCallback();
	    	}
	    };
    }
    headerDom.appendChild(jsDom);  
}
function importCSS(file){
	var head = document.getElementsByTagName('head').item(0);
	css = document.createElement('link');
	css.rel = 'stylesheet';
	css.href = file;
	css.type = 'text/css';
	head.appendChild(css);
}

function getIl8n(module,key){
	var value = null;
	if(typeof(key)=='undefined'){
		eval("value = mainWindow().il8n.basic_normal."+module+";");
	}else{
		eval("value = mainWindow().il8n."+module+"."+key+";");
		if(typeof(value)=='undefined'){
			eval("value = mainWindow().il8n.basic_normal."+key+";");
		}
	}
	return value;
}

var getTheSession = function(){
	return top.basic_user.loginData.session;
};

var getFormattedDate = function(date) {
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getYear()+1900 + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
    return currentdate;
};

var html_path = "";
var sys_top_window = false;
var mainWindow = function(){
	var theCurrentWindow = self;
	var count = 0;
	do{		
		if(theCurrentWindow.sys_top_window == true){
			//console.debug(theCurrentWindow);
			return theCurrentWindow;
		}
		else{
			count ++;
			theCurrentWindow = theCurrentWindow.parent;
			//console.debug(theCurrentWindow);
		}
	}while(theCurrentWindow.sys_top_window==false);
	return theCurrentWindow;
};
var openWindowOnTop = function(dialogPath,id,height,width,title){
	var mainWindow_ = mainWindow();
	var win = mainWindow_.$.ligerDialog.open({ 
         url: dialogPath
        ,height: height
        ,width: width
        ,title: title
		,isHidden: false                
		,showMax: true
		,showToggle: true
		,showMin: true
		,isResize: true
        ,id: id
		,modal: false                
    });  
	win.doMax();
};

var getWindowOnTop = function(id){
	var mainWindow_ = mainWindow();		
	return mainWindow_.$.ligerui.get(id);
};

function str2asc(strstr){ 
	return ("0"+strstr.charCodeAt(0).toString(16)).slice(-2); 
	} 
	function asc2str(ascasc){ 
	return String.fromCharCode(ascasc); 
	} 

var tools = {
	 setContent: function(dom){
		if($.ligerui.get("completeDemo_test") === undefined){
			var h = $(window).height();
			var w = $(window).width();
			var win = $.ligerDialog.open({ 
				url: '../libs/ueditor/_examples/completeDemo_test.html?random='+Math.random()
				,height: h - 90
				,width: w - 400
				,title: il8n.basic_normal.writeContent
				,isHidden: true
				, showMax: false
				, showToggle: false
				, showMin: false	
				, isResize: false
				, modal: false
				, id: 'completeDemo_test'
				, top: 5
				, left: 400
			});
		}
		else{	
			$.ligerui.get("completeDemo_test").show();
			var thedom = $.ligerui.get("completeDemo_test").frame;
			thedom.parentid = dom;
			thedom.setContent($(dom).val());
		}
	}	

	,UrlDecode:function(str){      
		  var ret="";      
		  for(var i=0;i<str.length;i++){      
		   var chr = str.charAt(i);      
		    if(chr == "+"){      
		      ret+=" ";      
		    }else if(chr=="%"){      
		     var asc = str.substring(i+1,i+3);      
		     if(parseInt("0x"+asc)>0x7f){      
		      ret+=asc2str(parseInt("0x"+asc+str.substring(i+4,i+6)));      
		      i+=5;      
		     }else{      
		      ret+=asc2str(parseInt("0x"+asc));      
		      i+=2;      
		     }      
		    }else{      
		      ret+= chr;      
		    }      
		  }      
		  return ret;      
		}  
	
	,uploadPhoto: function(){
		var path = $('[ligeruiid=photo]').val();
		
		$.ligerDialog.open({ 
			 content: "<iframe id='uploadPhoto_if' style='display:none' name='send'><html><body>x</body></html></iframe>" +
			 		"<form id='xx' method='post' enctype='multipart/form-data' action='"+config_path__uploadPhoto+"&executor="+top.basic_user.loginData.username+"&session="+top.basic_user.loginData.session+"' target='send'>" +
			 		"<table><tr><td><a class='btn_addPic' href='javascript:void(0);'><span><em>+</em>"+il8n.basic_normal.upload_chose_file+"</span><input class='filePrew' name='file' type='file' size=3  /></a>" +
			 		"</td><td>&nbsp;</td><td><input name='executor' value='"+top.basic_user.loginData.username+"' style='display:none' />" +			 				
			 		"<input name='session' value='"+top.basic_user.loginData.session+"' style='display:none' />" +			 						
			 		"<input type='submit' class='btn_addPic' value='"+il8n.basic_normal.submit+"' /></form>" +
			 		"</td></tr></table><br/><img id='uploadPhoto_img' src='"+path+"' />"
			,height: 400
			,width: 350
			,isHidden: false
			, showMax: false
			, showToggle: false
			, showMin: false	
			, isResize: false
			, modal: false			
			,title: il8n.basic_normal.upload
			,id: "uploadPhoto"
		});
		
		$(".l-dialog-content",$('#uploadPhoto')).removeClass("l-dialog-content");

		$("#uploadPhoto_if").load(function(){
	        var d = $("#uploadPhoto_if").contents();	
	        var s = $('body',d).html() ;
	        if(s=='')return;
	        eval("var obj = "+s);
	        if(obj.status=='1'){
	        	$('[ligeruiid=photo]').val(obj.path);
	        	$('#uploadPhoto_img').attr("src",obj.path);
	        	$("#img_photo").attr("src",obj.path);
	        }
	    });  
	}
};

