/*开始加载动画*/
var load_start = function(){
    document.getElementById('loading1').style.display='block';
};
/*关闭加载动画*/
var load_stop = function(){
    document.getElementById("loading1").style.display="none";
};

// /********************* 返回上一页 **********************/.
var hisReferrer = document.referrer.indexOf('zhaoshang800')>-1 && document.referrer.indexOf('sims')==-1;
if(hisReferrer){
    $(".headBox .back, .headBox .iback").attr("href", "javascript:void\(0\);");
}else{
    $(".headBox .back, .headBox .iback").attr("href", "https://m.zhaoshang800.com/");
}
$(".headBox .back, .headBox .iback").tap(function(event){
    if(hisReferrer){
        window.history.back();
    }
    event.preventDefault();//阻止默认行为
})
$(".back404").tap(function(event){
    window.history.back();
    event.preventDefault();//阻止默认行为
})
// $('header').on('click','.back',function(event){
//     window.history.back();
//     event.preventDefault();//阻止默认行为
// });

// if (sessionStorage.pagecount) {
//     sessionStorage.pagecount=Number(sessionStorage.pagecount) +1;
// }else {
//     sessionStorage.pagecount=1;
// }
// console.log(sessionStorage.pagecount)


// $('header .back').click(function(event){
//     window.history.go(-1);
//     event.preventDefault();//阻止默认行为
// });
/********************* 顶部抽屉导航 **********************/
var nav = function(e){
    var nav = document.getElementById('nav');
    var navCon = document.createElement('div')
    var navHead = document.createElement('div');
    var navUl = document.createElement('ul');
    var navFoot = document.createElement('div');
    var mask = document.createElement('div');//遮罩层
    var close = document.createElement('div');//关闭
    navCon.className = 'navCon';
    close.className = 'close';
    //navHead
    navHead.className="head";
    navHead.innerHTML = '<img src="/NewStyles/images/navigation_banner.png" alt=""> ';
    //navUl
    var pTxt = ['厂房','写字楼','土地','市县招商','产业园区','精选楼盘','经纪人','委托需求','投放房源','资讯'];
    navUl.className = 'menuNav clearFix';
    for(var i=0;i<pTxt.length;i++){
        var navLi = document.createElement('li');
        navLi.innerHTML = '<a href="'+route[i]+'">\n' +
            '                <img src="/NewStyles/images/nav_icon_'+i+'.png" alt="">\n' +
            '                <p>'+pTxt[i]+'</p>\n'
        '           </a>\n';
        navUl.appendChild(navLi)
    };
    //navFoot
    navFoot.className='foot clearFix';
    navFoot.innerHTML = '<a href="https://m.zhaoshang800.com/">首&nbsp;&nbsp;&nbsp;&nbsp;页</a>' +
        '<a href="https://m.zhaoshang800.com/aboutUs/">关于我们</a>'+
        '<a href="http://m.zhaoshang800.cn/">集团官网</a>'+
        '<a href="https://m.zhaoshang800.com/work/">业务合作</a>'
    //navMask
    mask.className='mask'
    //追加
    navCon.appendChild(navHead);
    navCon.appendChild(navUl);
    navCon.appendChild(navFoot);
    navCon.appendChild(close);
    nav.appendChild(navCon);
    nav.appendChild(mask);

}

/**
 * 列表页条件筛选请求
 * @param url
 * @param json
 * @returns {boolean}
 */
function ajaxListCondition(url,json){
    if(url == '' || url == undefined){
        alert('请求异常');
        return false;
    }
    $.ajax({
        type : 'post',
        url  : url,
        data : json,
        success:function(msg){
            location.href = msg;
        },
        error:function(){
            alert('网络错误');
        }
    })
}

/********************* 如果当前不是精选专题的就清除web存储 **********************/
if(location.href!="https://m.zhaoshang800.com/suiningzs/sxzs/tzcb/"){
    localStorage.removeItem('sxProService');//清空数据函数
}
if(location.href!="https://m.zhaoshang800.com/suiningzs/sxzs/xcjs/"){
    localStorage.removeItem('article');//清空数据函数
}
if(location.href!="https://m.zhaoshang800.com/foot/footprint/"){
    localStorage.removeItem('hisIndex');//清空数据函数
    localStorage.setItem("hisIndex",0);
}
/********************* 打开、关闭nav **********************/
var switchNav = function(){
    $(".headBox .nav").on("click",function(event){
            $("#nav").animate({
            display:'block',
        }, 200, 'ease-out');
        $("#nav>div.navCon").animate({
            transform: "translate3d(0,0,0)",
        }, 200, 'ease-out');
        // return false;
        $("body").addClass('modal-open')
        event.preventDefault();//阻止默认行为
    })
    //关闭
    $("#nav .close,#nav .mask,#nav .navIcon").click(function(event){

        $("#nav>div.navCon").animate({
            transform: "translate3d(0,0,0)"
        }, 200, 'ease-out');
        setTimeout(function(){
            document.getElementById('nav').style.display='none'

        },200);
        $("body").removeClass('modal-open')
        event.preventDefault();//阻止默认行为

    })
}

/********************* 搜索事件 **********************/
// 搜索tab
// var hotSearArr = [["标准厂房","标","标准厂","标准厂房"],["宝安写字楼","交通便利","带装修","近地铁口","深圳湾科技生态园","星河word","高新奇科技园","龙华写字楼出租"],["工业土地出售","地皮出售","土地买卖","南京土地","成都土地"],["粤海装备技术产业园","帝豪智造科技园","江门智能制造产业园","霸州智能测控装备产业园"]];
// var hotSearConTxt="";
// var hotSearCon = $(".hotSearch .hotSearCon");
// hotSearCon.html("");
// for(var i=0;i<hotSearArr[0].length;i++){
//     hotSearConTxt += "<a href='#'>"+hotSearArr[0][i]+"</a>"
// }
// hotSearCon.html(hotSearConTxt);

$(".searchWrapCon .tab li a").tap(function(){
    // hotSearConTxt="";
    // hotSearCon.html("");
    var now = $(this).parents("li").index();
    // for(var i=0;i<hotSearArr[now].length;i++){
    //     hotSearConTxt += "<a href='#'>"+hotSearArr[now][i]+"</a>"
    // }
    // hotSearCon.html(hotSearConTxt);
    var hotLi = $(".hotSearch .hotSearCon ul li");
    hotLi.eq(now).addClass("focus").siblings("li").removeClass("focus")
    if(now==4){
        $(".hotSearch").hide();
        $(".history").css('margin-top','0.2rem')
    }else{
        $(".hotSearch").show();
        $(".history").css('margin-top',0)
    }
});


var searchWrap = function(){
    $(".iSearchLink,.serchIcon").click(function(e){
        setTimeout(function(){
            $(".searchWrapBox").show()
            $("body").addClass("modal-open")
        },100);
        e.preventDefault();
        return false;
    });

    var val = $(".searchWrapBox .formBox input").val();
    //获得焦点
    // $(".searchWrapBox .formBox input").on('focus',function(){
    //     if(val=="请输入关键词进行搜索" || val==""){
    //         $(this).val("");
    //         console.log(val)
    //     }
    // });
    //实时监听input

    $(".searchWrapBox .tab li a").tap(function(){
        $(this).parent("li").addClass("foucs").siblings().removeClass("foucs");
        var bodyAttr = $('body').attr('data')
        if(bodyAttr=='officeBody'){
            var now = $(this).parents("li").index();
            switch (now){
                case 0:
                    focusIndex  =   'szoffice';
                    break;
                case 1:
                    focusIndex  =   'szoffice';
                    break;
                case 2:
                    focusIndex  =   'lp';
                    break;
            }
        }else{
            focusIndex = $(this).parents("li").index();
            if(typeof focusIndex22!="undefined"){
                focusIndex = focusIndex22;
            }
        }
    });
    //keyup的兼容性处理2.4
    var bind_name = 'input';

    if (navigator.userAgent.indexOf("msie") != -1){
        bind_name = 'propertychange';
    }//（此处是为了兼容ie）
    if(navigator.userAgent.match(/android/i) == "android") {
        bind_name = "keyup";
    }

    $('.searchWrapBox .formBox').on(bind_name, function(e) {
        var val = $(this).find('input').val();
        if(val=="请输入关键词进行搜索" || val==""){
            $(" .resetIco").hide();
            $(".searchWrapBox").removeClass("searchWrapFix");
            $(".searchWrapCon").removeClass('hide');
            $(".searchList").hide();
            $("body").removeClass("modal-open");
        }else if(val!=""||val!=null){
            $(" .resetIco").show();
            $(".searchWrapBox").addClass("searchWrapFix");
            $(".searchWrapCon").addClass('hide');
            $(".searchList").show();
            $("body").addClass("modal-open");
        }

        if(city == ''){
            wTem = {};
        }else{
            wTem = {"citySeo":city};
        }
        if(focusIndex==1 || focusIndex == 'lp' || focusIndex == 'szoffice'){
            wTem.xiezilou_keyword=val;
        }else if(focusIndex==5){
            wTem.factorykeyword=val;//厂房
        }else if(focusIndex==4){
            wTem.cangkukeyword=val;//仓库
        }else if(focusIndex==2){
            wTem.landkeyword=val;//土地
        }else if(focusIndex=='xm'){
            wTem.sxzskeyword=val;//市县
        }else{
            wTem.keyword=val;
        }

        // if(focusIndex==1){
        //     var url =   'https://m.zhaoshang800.com/Newoffice/list/';
        // }else if(focusIndex==2){
        //     var url =   'https://m.zhaoshang800.com/Newland/list/';
        // }else if(focusIndex==3){
        //     var url =   'https://m.zhaoshang800.com/park/list/';
        // }else if(focusIndex == 4){
        //     var url =   'https://m.zhaoshang800.com/Warehouse/list/';
        // }else if(focusIndex == 6){
        //     var type = $("input[name='type']").val();
        //     var url  = $("input[name='url']").val();
        //     wTem.type=type;
        // }else if(focusIndex == 'xm'){
        //     var url  = $("input[name='url']").val();
        // }else{
        //     var url =   'https://m.zhaoshang800.com/Newfactory/list/';
        // }

        if(focusIndex==1){
            var url =   'https://m.zhaoshang800.com/Newoffice/list/';
        }else if(focusIndex==2){
            var url =   'https://m.zhaoshang800.com/Newland/list/';
        }else if(focusIndex==3){
            var url =   'https://m.zhaoshang800.com/park/list/';
        }else if(focusIndex == 4){
            var url =   'https://m.zhaoshang800.com/Warehouse/list/';
        }else if(focusIndex == 6){
            var type = $("input[name='type']").val();
            var url  = $("input[name='url']").val();
            wTem.type=type;
        }else if(focusIndex == 'xm'){
            var url  = $("input[name='url']").val();
        }else if(focusIndex == 'lp'){
            var type = $("input[name='type']").val();
            var url =   'https://m.zhaoshang800.com/office/xzl/';//楼盘
            wTem.type=type;
        }else if(focusIndex == 'szoffice'){
            var type = $("input[name='type']").val();
            var url =   'https://m.zhaoshang800.com/newoffice/szlist/';//深圳写字楼
            wTem.type=type;
        }else{
            var url =   'https://m.zhaoshang800.com/Newfactory/list/';
        }





        var ajaxTimeoutTest = $.ajax({
            type:"post",//请求方式
            url:url,//地址，就是json文件的请求路径
            // dataType:"json",
            data:{
                wTem:wTem,
                ajax:'ajaxLs',
            },
            timeout:3000,//超时时间设置，单位毫秒
            success:function (data) {
                var res = JSON.parse(data).list;
                var searchList = $(".searchList ul");
                var searchCon = "";
                searchList.html("");
                if(res.length!=0){
                    $('header').attr('id','');
                    searchList.html("");
                    for (var i=0;i<res.length;i++){
                        searchCon+="<li class='searchContent'>\n" +
                            "                    <a href='"+res[i].url+"' onclick='_czc.push([\"_trackEvent\",\"搜索页\",\"搜索结果页点击模糊匹配进入详情页次数\"]);'>\n" +
                            "                        <p>"+res[i].title+"</p>\n" +
                            "                        <p>"+res[i].add+"</p>\n" +
                            "                    </a>\n" +
                            "                </li>"
                    }
                    searchList.html(searchCon);
                }else{
                    searchCon=''
                    searchList.html(searchCon);
                    $(".searchWrapBox .formBox .form").submit(function(e){
                        searchCon='<li class="notSearchList">您输入的关键查找不到相关的内容，请更换关键词</li>';
                        searchList.html(searchCon);
                        e.preventDefault();
                        return false;
                    });
                    $('header').attr('id','noSearch');
                }
            },
            error:function (error) {
                console.log(error)
            },
            complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
                var searchList = $(".searchList ul");
                var searchCon = "";
                if(status=='timeout'){//超时,status还有success,error等值的情况
                    $('header').attr('id','noSearch');
                    searchList.html("");
                    $(".searchWrapBox .formBox .form").submit(function(e){
                        searchCon='<li class="notSearchList">网络延迟，响应超时！</li>';
                        searchList.html(searchCon);
                        e.preventDefault();
                        return false;
                    })
                    ajaxTimeoutTest.abort();

                }
            }


        })



    });



    $(".searchWrapBox .formBox .form").submit(function(e){
        var attrid = $('header').attr('id');
        if(attrid == 'noSearch'){
            return false;
            e.preventDefault();
        }
        var formF = $(".formBox form.form");
        var url =   formF.attr('url');
        var keyword =   $("input[name='searInp']").val();
        if(focusIndex==1 || focusIndex=='lp' || focusIndex == 'szoffice'){
            var key =   url+'?xiezilou_keyword='+keyword;
        }else if(focusIndex == 5){
            var key =   url+'?factorykeyword='+keyword;//厂房
        }else if(focusIndex == 4){
            var key =   url+'?cangkukeyword='+keyword;//仓库
        }else if(focusIndex == 2){
            var key =   url+'?landkeyword='+keyword;//土地
        }else if(focusIndex == 'xm'){
            var key =   url+'?sxzskeyword='+keyword;//土地
        }else{
            var key =   url+'?keyword='+keyword;
        }
        location.href = key;
        e.preventDefault();
    })



    //清除搜索内容
    $(".searchWrapBox .resetIco,.searchWrapBox .formBox .cancel").tap(function(){
        // $(".searchForm input").val("");
        $(this).siblings('input').trigger('focus');
        $(this).siblings("input[name='searInp']").val('');
        $(this).siblings('textarea').trigger('focus');
        $(this).siblings('textarea').val('');


        $(" .resetIco").hide();
        $(".searchWrapBox").removeClass("searchWrapFix");
        $(".searchWrapCon").removeClass('hide');
        $(".searchList").hide();
        $("body").removeClass("modal-open");
    });
    //失去焦点
    // $(".searchWrapBox .formBox input").on('blur',function(){
    //     if(val=="请输入关键词进行搜索" || val==""){
    //         $(this).val("请输入关键词进行搜索")
    //     }
    // });
    //清除历史记录
    $(".searchWrapBox .history .empty").tap(function(){
        $(".MessageBox").animate({
            display:'block',
        }, 500, 'ease-out');
        // $(this).hide();
    });
    //取消搜索页面
    $(".searchWrapBox .formBox .cancel").click(function(){
        $(".searchWrapBox").hide();
        $("body").removeClass("modal-open");
    });

    //搜索历史
    var historyLi = $(".searchWrapCon .history .historyUl li:not(.notHistoty)");
    var historyUl = $(".searchWrapCon .history .historyUl");
    var notHistoty = $(".searchWrapCon .history .historyUl li.notHistoty")
    var cancelEmpty = $(".searchWrapBox .MessageBox .btn .cancel");
    var sureEmpty = $(".searchWrapBox .MessageBox .btn .sure");
    cancelEmpty.click(function(){
        // $(".MessageBox").animate({
        //     display:'none',
        // }, 500, 'ease-out');
        $(".MessageBox").css({"display":"none"})
    });
    sureEmpty.tap(function(){
        var historyLi = $(".searchWrapCon .history .historyUl li:not(.notHistoty)");
        var historyUl = $(".searchWrapCon .history .historyUl");
        historyLi.remove();
        notHistoty.show();
        $(".MessageBox").animate({
            display:'none',
        }, 500, 'ease-out');
        $(".searchWrapBox .history .empty").hide();
    })
    //如果没有搜索历史就显示暂无记录
    if (historyLi.length>0){
        notHistoty.hide();
    }else{
        notHistoty.show();
        var hisLi = '<li class="notHistoty">\n' +
            '                        暂无搜索历史记录\n' +
            '                    </li>'
        historyUl.html(hisLi)
    }

}




/********************* icon左右模块对齐  中间模块居中 **********************/
var justCenter = function(num2){
    // var justList = $('#justify,#nav').find('li');
    // var justLast = $('#justify,#nav').find('li:nth-of-type(4n)');
    // var justLsDiv = $('#justify,#nav').find('li div')
    // justList.css({ 'float':'left' , 'text-align':'left' });
    // justLast.css({'text-align':'right'});
    // justLsDiv.css({'text-align ':'center' , 'display':'inline-block'})
    // var justWid = $('#justify').width();
    // var justLiLastWid = $('#justify').find('li:last-child div,li:last-child a').width();
    // justList.width( (justWid -justLiLastWid) / (4-1)  );
    // justLast.width(justLiLastWid);

    if(num2=='justify'){
        var justList2 = $('#'+num2).find('li');
        var justLast2 = $('#'+num2).find('li:nth-of-type(4n)');
        var justLsDiv2 = $('#'+num2).find('li div,li a');
        justList2.css({ 'float':'left' , 'text-align':'left' });
        justLast2.css({'text-align':'right'});
        justLsDiv2.css({'text-align ':'center' , 'display':'inline-block'});
        // var justWid2 = $('#'+num2).width();
        var justWid2 = $("#"+num2)[0].getBoundingClientRect().width
        // var justLiLastWid = $('#'+num).find('li:last-child div').width();
        var last = $("#"+num2)[0].children.length-1;
        var justLiLastWid2 = $("#"+num2)[0].children[last].getBoundingClientRect().width;
        justList2.width( (justWid2 -justLiLastWid2) / (4-1)  );
        justLast2.width(justLiLastWid2);

    }


}

/********************* 返回顶部 **********************/
function goTop(acceleration, time) {
    acceleration = acceleration || 0.1;
    time = time || 16;
    var x1 = 0;
    var y1 = 0;
    var x2 = 0;
    var y2 = 0;
    var x3 = 0;
    var y3 = 0;
    if (document.documentElement) {
        x1 = document.documentElement.scrollLeft || 0;
        y1 = document.documentElement.scrollTop || 0;
    }
    if (document.body) {
        x2 = document.body.scrollLeft || 0;
        y2 = document.body.scrollTop || 0;
    }
    var x3 = window.scrollX || 0;
    var y3 = window.scrollY || 0;
// 滚动条到页面顶部的水平距离
    var x = Math.max(x1, Math.max(x2, x3));
// 滚动条到页面顶部的垂直距离
    var y = Math.max(y1, Math.max(y2, y3));
// 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
    var speed = 1 + acceleration;
    window.scrollTo(Math.floor(x / speed), Math.floor(y / speed));
// 如果距离不为零, 继续调用迭代本函数
    if (x > 0 || y > 0) {
        var invokeFunction = "goTop(" + acceleration + ", " + time + ")";
        window.setTimeout(invokeFunction, time);
    }
}
$("#goToUp").tap(function() {
    //$("body").scrollTop(0);
    //window.scrollTo(0,0);
    // goTop();
    $(window).scrollTop("0");
});
$(window).scroll(function(){
    var _top=$(window).scrollTop();
    if(_top>200){
        $("#goToUp").show();
    }else{
        $("#goToUp").hide();
    }
})

/**************************** 分享组件 ***************************/
var share = function(){
    /* 获取当前环境：
    系统环境： iOS Android PC
    浏览器环境 微信内置浏览器、QQ内置浏览器、正常浏览器
    是否app内打开
*/
    var ua = navigator.userAgent.toLowerCase(); //获取浏览器标识并转换为小写
    // alert(ua)
    var curConfig = {
        isiOS: !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //是否苹果
        isAndroid: ua.indexOf('android') > -1 || ua.indexOf('adr') > -1, //是否安卓
        isPC: isPC(), //是否PC
        isWeiXin: ua.match(/MicroMessenger/i) == "micromessenger", //是否微信
        isQQ: ua.indexOf(' qq/') > -1, //是否QQ
        isDingtalk: ua.indexOf('dingtalk') > -1, //钉钉
        isWeibo: ua.indexOf('weibo') > -1, //微博
        isUC: ua.indexOf('ucbrowser') > -1, //uc
        isBaiduapp: ua.indexOf('baiduboxapp') > -1, //微博
        isApp: ua.indexOf('isApp') > -1, //是否某个应用
        isChrome:ua.indexOf('chrome')>-1,//chrome浏览器
        isXiaoMi:ua.indexOf('xiaomi')>-1,//小米浏览器
    };

    function isPC() {
        var Agents = new Array("android", "iphone", "symbianOS", "windows phone", "ipad", "ipod");
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (ua.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    }
    var nativeShare = new NativeShare()
    var title = $('title').html()
    var logo = $('#logo').attr('logo')
    var description = $('meta[name="description"]').attr('content')
    var shareData = {
        title: title,
        desc: description,
        // 如果是微信该link的域名必须要在微信后台配置的安全域名之内的。
        link: window.location.href,
        icon: logo,
        // 不要过于依赖以下两个回调，很多浏览器是不支持的
        success: function() {
            // alert('success')
        },
        fail: function() {
            alert('fail')
        }
    }
    nativeShare.setShareData(shareData);
    function setTitle(title) {
        nativeShare.setShareData({
            title: title,
        })
    }

    //<a href="javascript:void(0)" class="share" onclick="call(command)"></a>
    // function call(command) {
    //
    //
    //
    // }
    $(".headBox .share").tap(function(){
        try {
            nativeShare.call("command");
            if(curConfig.isWeiXin || curConfig.isDingtalk || curConfig.isWeibo){
                $(".sharePageBpx .share-1").show();
            }
            // if(curConfig.isUC || curConfig.isAndroid){
            //     $(".sharePageBpx .share-3").show();
            // }
        } catch (err) {
            // 如果不支持，你可以在这里做降级处理
            if(curConfig.isWeiXin || curConfig.isDingtalk || curConfig.isWeibo){
                $(".sharePageBpx .share-1").show();
            }else{
                $(".sharePageBpx .share-3").show();
            }

        }
    });

    $(".sharePageBpx>div.share .share-btn").tap(function(event){
        $(this).parents(".share").hide();
        event.stopPropagation();//阻止默认行为

    })

    // $(".sharePageBpx>div.share .share-btn").on("click", function(event){  //这里使用touchstart事件也可以
    //     $(this).parents(".share").hide();
    //     event.stopPropagation();//阻止默认行为
    // });
}

/**************************** 滚动公告 ***************************/
// var notice = function () {
//     //初始化
//     var notice = document.getElementById("notice");
//     var noticeCon = notice.children[0];
//     var noticeP = notice.children[0].children;
//     //设置UL的高度
//     noticeCon.style.height = (noticeP.length)*0.8+"rem";
//
//     //播放函数
//     var n=0;
//     function run(){
//         if(n<noticeP.length-1){
//             n=n+1;
//         }else{
//             noticeCon.style.marginTop = "0rem"
//             n=1;
//         }
//         // noticeCon.animate({marginTop:(-0.8*n)+"rem"},400);//marginTop 向上移动
//         $("#notice .noticeCon").animate({
//             "margin-top":(-0.8*n)+"rem"
//         }, 400, 'ease-out');
//     }
//     var timer = setInterval(run,3000);
// }
// notice();
/********************* 头部滑动 **********************/
var headFixBox = function(){
    var deBanner = $(".deBanner");
    if( deBanner.length==0||deBanner==null){
        var h = 200;
    }else{
        var h = deBanner.height();
    }
    var headBoxH = $(".headBox").height()
    $(window).scroll(function(){
        var top=$(window).scrollTop() || document.documentElement.scrollTop || document.body.scrollTop;

        if(top>headBoxH){
            $(".headBox").addClass("headFixBox")
        }else{
            $(".headBox").removeClass("headFixBox");
        }
        if(top>h){
            $(".headBox").css("background-color","rgba(248,248,248,1)")
        }else{
            var op = top/h * 1;
            $(".headBox").css("background-color","rgba(248,248,248,"+op+")")
        }
    })
}
var titleFixBox = function(){
    var rdeBanner = $(".rheadFixBox");
    var rheadBoxH = $(".rheadBox").height();
    $(window).scroll(function(){
        var top=$(window).scrollTop() || document.documentElement.scrollTop || document.body.scrollTop;
        if(top>rheadBoxH){
            $(".rheadBox").addClass("rheadFixBox");
            if($(".newsSelectBox").length==1){
                $(".newsSelectBox").addClass("fix");
            }
        }else{
            $(".rheadBox").removeClass("rheadFixBox");
            if($(".newsSelectBox").length==1){
                $(".newsSelectBox").removeClass("fix");
            }
        }
    })
}
var rheadFixBox = function(){
    // var rheadBox = $(".rheadBox");
    // var rheadBoxH = $(".rheadBox").height();
    // if(Boolean(rheadBox)){
    //     var div = '<div class="headSeat" style="height:'+rheadBoxH+'px">ss</div>';
    //     rheadBox.after(div);
    // }
}


/********************* ad **********************/
var adSwiper = function () {
    adSwiper = new Swiper('.adBox', {
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
        },
        loop : true,

    })
}
var footFixA = function () {
    var mainH = $("main").height();
    var headH = $("header").height();
    var botFixNavBoxH = $(".botFixNavBox").height();
    var wH = $(window).height();
    var w = wH-mainH-headH
    if ((mainH + headH) < wH) {
        // $("footer").addClass("footFix")
        $("footer").before("<div class='aa' style='height: "+w+"px;visibility: hidden'>s</div>")
    }
}
var footFixList = function () {
    var huiWrapH = document.getElementById("refreshContainer").outerHeight();
    var headH = $("header").height();
    var wH = $(window).height();
    // if ((huiWrapH + headH) < wH) {
    //     $("footer").addClass("footFix")
    // }
    // console.log(huiWrapH+"1,"+headH+","+wH)
}
/**
 * sem获取拨打电话记录
 */
// $('.semCall').on('click', function () {
//     var url = window.location.href;
//     $.ajax({
//         type : 'post',
//         url : 'https://m.zhaoshang800.com/index.php?c=main&a=semtelsub',
//         data : {url:url},
//         success: function (ret) {
//             console.log(ret);
//         }
//     })
//     console.log(url);
// })





/*
$(".openAppA").click(function(){
    //判断浏览器
    var u = navigator.userAgent;
    if(/MicroMessenger/gi.test(u)){
        // 引导用户在浏览器中打开
        // alert('请在浏览器中打开');
        //在微信中打开 引导用户在浏览器中打开
        $("#openTips").show();
        return;
    }
    var d = new Date();
    var t0 = d.getTime();
    if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
        //Android
        if(openApp('com.zhaoshang800.partner.zg://')){
            openApp('com.zhaoshang800.partner.zg://');
        }else{
            //由于打开需要1～2秒，利用这个时间差来处理－－打开app后，返回h5页面会出现页面变成app下载页面，影响用户体验
            var delay = setInterval(function(){
                var d = new Date();
                var t1 = d.getTime();
                if( t1-t0<3000 && t1-t0>2000){
                    // alert('请下载APP');
                    window.location.href = "https://hb-store-public.oss-cn-shenzhen.aliyuncs.com/broker/dl/app-place-1.2.1-release.apk";
                }
                if(t1-t0>=3000){
                    clearInterval(delay);
                }
            },1000);
        }
    }else if(u.indexOf('iPhone') > -1){
        //IOS
        if(openApp('xuanzhiyi://')){
            openApp('xuanzhiyi://');
        }else{
            var delay = setInterval(function(){
                var d = new Date();
                var t1 = d.getTime();
                if( t1-t0<3000 && t1-t0>2000){
                    // alert('请下载APP');
                    window.location.href = "https://itunes.apple.com/cn/app/id1159301055?mt=8";
                }
                if(t1-t0>=3000){
                    clearInterval(delay);
                }
            },1000);
        }
    }
})

function openApp(src) {
// 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
// 否则打开a标签的href链接
    var ifr = document.createElement('iframe');
    ifr.src = src;
    ifr.style.display = 'none';
    document.body.appendChild(ifr);
    window.setTimeout(function(){
        document.body.removeChild(ifr);
    },2000);
}
*/



$(".openAppB").click(function(){
    //判断浏览器
    var u = navigator.userAgent;
    var ua = navigator.userAgent.toLowerCase(); //获取浏览器标识并转换为小写
    // alert(ua)
    var curConfig = {
        isiOS: !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //是否苹果
        isAndroid: ua.indexOf('android') > -1 || ua.indexOf('adr') > -1, //是否安卓
        // isPC: isPC(), //是否PC
        isWeiXin: ua.match(/MicroMessenger/i) == "micromessenger", //是否微信
        isQQ: ua.indexOf(' qq/') > -1, //是否QQ
        isDingtalk: ua.indexOf('dingtalk') > -1, //钉钉
        isWeibo: ua.indexOf('weibo') > -1, //微博
        isUC: ua.indexOf('ucbrowser') > -1, //uc
        isBaiduapp: ua.indexOf('baiduboxapp') > -1, //百度
        isApp: ua.indexOf('isApp') > -1, //是否某个应用
        isChrome:ua.indexOf('chrome')>-1,//chrome浏览器
        isXiaoMi:ua.indexOf('xiaomi')>-1,//小米浏览器
    };
    if(curConfig.isWeiXin||curConfig.isQQ||curConfig.isWeibo){
        // 引导用户在浏览器中打开
        // alert('请在浏览器中打开');
        //在微信中打开 引导用户在浏览器中打开
        $("#openTips").show();
        return;
    }
    var d = new Date();
    var t0 = d.getTime();
    if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
        //Android
        if(openApp('com.zhaoshang800.partner.zg://')){
            openApp('com.zhaoshang800.partner.zg://');
            // window.location.href='xuanzhiyi://factorydetail?data={}'
        }else{
            //由于打开需要1～2秒，利用这个时间差来处理－－打开app后，返回h5页面会出现页面变成app下载页面，影响用户体验
            var delay = setInterval(function(){
                var d = new Date();
                var t1 = d.getTime();
                if( t1-t0<3000 && t1-t0>2000){
                    // alert('请下载APP');
                    window.location.href = "https://hb-store-public.oss-cn-shenzhen.aliyuncs.com/broker/dl/place-release-1.7.0_20190722093541814.apk";
                }
                if(t1-t0>=3000){
                    clearInterval(delay);
                }
            },2000);
        }
    }else if(u.indexOf('iPhone') > -1){
        //IOS
        if(openApp('xuanzhiyi://')){
            // openApp('xuanzhiyi://');
            // var url='xuanzhiyi://';
            window.location.href = 'xuanzhiyi://';
        }else{
            var delay = setInterval(function(){
                var d = new Date();
                var t1 = d.getTime();
                if( t1-t0<3000 && t1-t0>2000){
                    // alert('请下载APP');
                    window.location.href = "https://itunes.apple.com/cn/app/id1159301055?mt=8";
                }
                if(t1-t0>=3000){
                    clearInterval(delay);
                }
            },2000);
        };
        // var url='xuanzhiyi://';
        // window.location.href = url;
        // var delay = setInterval(function(){
        //     var d = new Date();
        //     var t1 = d.getTime();
        //     if( t1-t0<3000 && t1-t0>2000){
        //         // alert('请下载APP');
        //         window.location.href = "https://itunes.apple.com/cn/app/id1159301055?mt=8";
        //     }
        //     if(t1-t0>=3000){
        //         clearInterval(delay);
        //     }
        // },2000);
        // window.location.href = "xuanzhiyi://partner/landdetail?id=2496087316562071552";
        if(u.indexOf('Safari') > -1){
            setTimeout(function(){
                window.location.reload()
            },2100)
        }

    }else{
        alert('请在手机浏览器打开！')
    }
})
$(".openAppA").click(function(){
    //判断浏览器
    var u = navigator.userAgent;
    var ua = navigator.userAgent.toLowerCase(); //获取浏览器标识并转换为小写
    // alert(ua)
    var curConfig = {
        isiOS: !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //是否苹果
        isAndroid: ua.indexOf('android') > -1 || ua.indexOf('adr') > -1, //是否安卓
        // isPC: isPC(), //是否PC
        isWeiXin: ua.match(/MicroMessenger/i) == "micromessenger", //是否微信
        isQQ: ua.indexOf(' qq/') > -1, //是否QQ
        isDingtalk: ua.indexOf('dingtalk') > -1, //钉钉
        isWeibo: ua.indexOf('weibo') > -1, //微博
        isUC: ua.indexOf('ucbrowser') > -1, //uc
        isBaiduapp: ua.indexOf('baiduboxapp') > -1, //百度
        isApp: ua.indexOf('isApp') > -1, //是否某个应用
        isChrome:ua.indexOf('chrome')>-1,//chrome浏览器
        isXiaoMi:ua.indexOf('xiaomi')>-1,//小米浏览器
    };
    if(curConfig.isWeiXin||curConfig.isQQ||curConfig.isWeibo){
        // 引导用户在浏览器中打开
        // alert('请在浏览器中打开');
        //在微信中打开 引导用户在浏览器中打开
        // alert('请在浏览器打开');
        $("#openTips").show();
        return;
    }
    var d = new Date();
    var t0 = d.getTime();
    if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
        //Android
        if(typeof xzyType !="undefined"){
            if(openApp('com.zhaoshang800.partner.zg://partner/'+xzyName+'?id='+xzyID+'&xzyType='+xzyType)){
                openApp('com.zhaoshang800.partner.zg://partner/'+xzyName+'?id='+xzyID+'&xzyType'+xzyType);
                // window.location.href='xuanzhiyi://factorydetail?data={}'
            }else{
                //由于打开需要1～2秒，利用这个时间差来处理－－打开app后，返回h5页面会出现页面变成app下载页面，影响用户体验
                var delay = setInterval(function(){
                    var d = new Date();
                    var t1 = d.getTime();
                    if( t1-t0<3000 && t1-t0>2000){
                        // alert('请下载APP');
                        window.location.href = "https://hb-store-public.oss-cn-shenzhen.aliyuncs.com/broker/dl/place-release-1.7.0_20190722093541814.apk";
                    }
                    if(t1-t0>=3000){
                        clearInterval(delay);
                    }
                },2000);
            }
        }else{
            if(openApp('com.zhaoshang800.partner.zg://partner/'+xzyName+'?id='+xzyID)){
                openApp('com.zhaoshang800.partner.zg://partner/'+xzyName+'?id='+xzyID);
                // window.location.href='xuanzhiyi://factorydetail?data={}'
            }else{
                //由于打开需要1～2秒，利用这个时间差来处理－－打开app后，返回h5页面会出现页面变成app下载页面，影响用户体验
                var delay = setInterval(function(){
                    var d = new Date();
                    var t1 = d.getTime();
                    if( t1-t0<3000 && t1-t0>2000){
                        // alert('请下载APP');
                        window.location.href = "https://hb-store-public.oss-cn-shenzhen.aliyuncs.com/broker/dl/place-release-1.7.0_20190722093541814.apk";
                    }
                    if(t1-t0>=3000){
                        clearInterval(delay);
                    }
                },2000);
            }
        }

    }else if(u.indexOf('iPhone') > -1){
        //IOS
        // if(openApp('xuanzhiyi://')){
        //     openApp('xuanzhiyi://partner/landdetail?id=2496087316562071552');
        // }else{
        //     var delay = setInterval(function(){
        //         var d = new Date();
        //         var t1 = d.getTime();
        //         if( t1-t0<3000 && t1-t0>2000){
        //             // alert('请下载APP');
        //             window.location.href = "https://itunes.apple.com/cn/app/id1159301055?mt=8";
        //         }
        //         if(t1-t0>=3000){
        //             clearInterval(delay);
        //         }
        //     },2000);
        // }
        if(typeof xzyType !="undefined"){
            var url='xuanzhiyi://partner/'+xzyName+'?id='+xzyID+'&xzyType='+xzyType;
        }else{
            var url='xuanzhiyi://partner/'+xzyName+'?id='+xzyID;
        }

        window.location.href = url;
        var delay = setInterval(function(){
            var d = new Date();
            var t1 = d.getTime();
            if( t1-t0<3000 && t1-t0>2000){
                // alert('请下载APP');
                window.location.href = "https://itunes.apple.com/cn/app/id1159301055?mt=8";
            }
            if(t1-t0>=3000){
                clearInterval(delay);
            }
        },2000);
        // window.location.href = "xuanzhiyi://partner/landdetail?id=2496087316562071552";


    }else{
        alert('请在手机浏览器打开！')
    }
})

function openApp(src) {
// 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
// 否则打开a标签的href链接
    var ifr = document.createElement('iframe');
    ifr.src = src;
    ifr.style.display = 'none';
    document.body.appendChild(ifr);
    window.setTimeout(function(){
        document.body.removeChild(ifr);
    },2000);
}

$("#openTips").click(function(){
    $(this).hide();
})
