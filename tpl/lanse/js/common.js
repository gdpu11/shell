function tishi(content,url) {
	var html = '<div class="xiaoxi none" id="msg" style="z-index:11001;left: 5%;width: 90%;position: fixed;background:none;top:50%;"> <p class="msg" style="background: none repeat scroll 0 0 #000; border-radius: 30px;color: #fff; margin: 0 auto;padding: 1.5em;text-align: center;width: 70%;opacity: 0.8;"></p></div>';
	$(document.body).append(html); 
	$("#msg").show();
	$(".msg").html(content);
	if(url){
	window.setTimeout("location.href='"+url+"'", 1500);
	}else{
	setTimeout('$(".msg").fadeOut()', 1500);
	}
} 