document.write("<script src='/tpl/lanse/js/moment.min.js'></script>");
Vue.filter('moment', function (value, formatString) {
    formatString = formatString || 'YYYY-MM-DD HH:mm:ss';
    return moment(parseInt(value) * 1000).format(formatString);
});

$(function(){
    var params = getParams();
	var url = '/Api/getMsg?'+params; 
	$.post(url,'',function(data){
		// console.log(data)
		pushDom(data);
	},"json");
})
function getParams()
{   
    var url = window.location.href;
    var params = url.split('?');
    if (params[1])return params[1];
    else return '';
}
function pushDom(data){
    var vm = new Vue({
        el: '.list',
        data: data,
        methods: {
        del: function (id) {
            if(confirm("您确定要删除吗?")){
                var params = getParams();
                var url = '/Api/delMsg?'+params; 
                $.post(url,{id:id},function(data){
                   if (data.code==1) {
                    alert('删除成功');
                   }else{
                    alert('删除失败');
                   }
                    window.location.href = data.url;
                },"json");
            }
        }
    }
    });
}