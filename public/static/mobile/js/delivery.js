$(".entrustType li, .sellType li").tap(function(){
    $(this).addClass("focus").siblings("li").removeClass("focus");
});


// 租售选择弹框
$("#expressBus").click(function(){
    $("#areaMask").show();
    $("body").addClass("modal-open");
    $("#BusBox").css({"visibility": "visible"}).attr("flag","0");
})
$("#expressBus").focus(function(){
    document.activeElement.blur();
});
/*关闭省市区选项*/
$("#areaMask, #closeArea").click(function() {
    clockBus();
});
if($("#expressBus").attr("value") == "出租"){
    $("#expressBus").attr("value",'出租');
}else{
    $("#expressBus").attr("value",'出售');
}
$("#BusConUl li").click(function(){
    var index =$(this).index();
    $(this).addClass('focus').siblings('li').removeClass('focus')
    var text = $(this).text();
    $("#expressBus").attr("value",text);
    $("#expressBus").val(text).addClass("active");
    if($("#expressBus").attr("value") == '出租'){
        $("#expressBus").attr("data-label",1);
    }else{
        $("#expressBus").attr("data-label",2);
    }
    clockBus();
})

/*租售选择弹框*/
function clockBus(){
    $("#areaMask").hide();
    $("body").removeClass("modal-open");
    // $("#areaLayer").animate({"bottom": "-100%"});
    $("#BusBox").css({"visibility": "hidden"}).attr("flag","0");
    // intProvince(1);
}
function dialog(type) {
    //type=1:成功 type=2:提交失败 type=3:服务器出错
    var type = arguments[0]?arguments[0]:1;
    var _this = $('.megDialog')
    _this.show();
    if(type==1){
        _this.addClass('megSuusen')
        _this.find('h4').text('信息提交成功');
        _this.find('.fail').text('请保持电话通畅，客服将会联系您');
    }else if(type==2){
        _this.removeClass('megSuusen').addClass('megFail')
        _this.find('h4').text('信息提交失败');
        _this.find('.fail').text('请重新提交');
    }else if(type==3){
        _this.removeClass('megSuusen').addClass('megFail')
        _this.find('h4').text('服务器出错');
        _this.find('.fail').text('请重新提交');
    }else if(type==4){
        _this.removeClass('megSuusen')
        _this.find('h4').text('请勿频繁提交');
        _this.find('.fail').text('您已成功提交，请勿频繁提交');
    }

    $(".megDialog").next('.maskBox').show();
    return false;
}


var inp = $(".demandCon .inp input");
var textarea = $(".demandCon .textarea textarea");
var telReg = /^((0\d{2,3}-\d{7,8})|(1[3456789]\d{9}))$/;//手机正则
var areaReg = /^[0]+[0-9]*$/gi
var areaReg2 = /^(\-)*(\d+)\.(\d\d).*$/
// var areaReg3 = /^[0-9]{1,8}([.][0-9]{1,5})?$/
$(".demandBox").append('<div class="verifyTipsBox center"><p class="verifyTips ">手机号码格式有误，请重新输入</p></div> ');
var verifyTipsBox = $(".verifyTipsBox");
var verifyTips = $(".verifyTipsBox .verifyTips");
/************ 设置自动消失事件 *************/
function dieAway(){
    verifyTipsBox.show();

    setTimeout(function(){
        $(".verifyTipsBox").animate({
            opacity:'0',
            // display:'none',
        }, 300, 'ease-out');
    },1000);
    $(".verifyTipsBox").css({"opacity":"1"})
    setTimeout(function () {
        verifyTipsBox.hide();
    },4000)
}
// 表单验证提交
$('form.demandCon').submit(function(event){
    if(!inp[0].value.trim() || inp[0].value.trim()=="请租售类别"){
        verifyTips.text("租售类别");
        dieAway();
        return false;
    }
    if(!inp[1].value.trim() || inp[1].value.trim()=="请选择位置"){
        verifyTips.text("请选择位置");
        dieAway();
        return false;
    }
    if(!inp[2].value.trim() || inp[2].value.trim()=="请输入面积"){
        verifyTips.text("请输入面积");
        dieAway();
        return false;
    }
    // if(areaReg3.test(inp[2].value.trim())){
    //     verifyTips.text("请输入正确的面积！");
    //     dieAway();
    //     return false;
    // }
    if(!textarea[0].value.trim() || textarea[0].value.trim()=="请输入您盘源的具体需求"){
        verifyTips.text("请输入您盘源的具体需求");
        dieAway();
        return false;
    }

    if(!inp[3].value.trim() || inp[3].value.trim()=="请输入联系人名称"){
        verifyTips.text("请输入联系人");
        dieAway();
        return false;
    }
    if(!inp[4].value.trim()){
        verifyTips.text("联系方式不能为空");
        dieAway();
        return false;
    }
    if(!telReg.test(inp[4].value)){
        verifyTips.text("您输入的手机格式有误，请重新输入");
        dieAway();
        return false;
    }
    var all= $('#expressArea').attr('data-label');
    var people = $('#people .focus').attr('val');
    var classType = $('#classType .focus').attr('val');
    var type = $("input[name='type']").attr('data-label');
    var area = $("input[name='area']").val();
    var demand = $("#demand").val();
    var name = $("input[name='name']").val();
    var tel = $("input[name='tel']").val();
    $.ajax({
        type : 'post',
        url  : "https://m.zhaoshang800.com/sxzs/DiskSub/",
        data : {all:all,people:people,leibie:classType,leixing:type,area:area,detail:demand,name:name,tel:tel},
        success:function(msg){
            if(msg == 1){
                $('#sub').attr("disabled","disabled");
                $('#sub').css({"background-color":"#ccc"});
                $('#sub').html('已委托');
                dialog(msg)
            }else if(msg == 2){
                return  dialog(msg)
            }else if(msg == 4){
                return  dialog(msg)
            }
        },
        error:function(error){
            return  dialog(3);
        },
    });
    return false;
    // event.preventDefault();//阻止默认行为
})

$(".megDialog p.btn").tap(function(){
    var _this = $('.megDialog')
    _this.hide();
    $(".megDialog").next('.maskBox').hide();
})

$(".demandCon .sellType ul li").tap(function(){
    $(this).addClass("focus").siblings("li").removeClass("focus");
    if($(this).index()==0 || $(this).index()==2){
        $(".demandCon .inp .areaUnit").text("㎡");
    }else if($(this).index()==1){
        $(".demandCon .inp .areaUnit").text("亩");
    }
});