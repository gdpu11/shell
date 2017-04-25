$(function(){
	var url = $("#url").val();;
   $.post(url,$("#loginform").serialize(),function(data){
      if (data.status==1&&data.state=='success') {
          tishi(data.info,data.url);
      }else{
          tishi(data.info);
      }
    },
    "json");
}) 			
 
function check(form) {
	var thisForm = form;
	if(form.name.value=='') {
		tishi('请输入用户名');
		form.name.focus();
		return false;
	}
	if(form.password.value=='') {
	    tishi('请输入密码');
	    form.password.focus();
	    return false;
	}
	var url = './Test/login'
	$.post(url,thisForm.serialize(),function(data){
	  if (data.status==1) {
	      tishi(data.info);
	  }else{
	      tishi(data.info);
	  }
	},
	"json");//这里返回的类型有：json,html,xml,text
	return false;
 }