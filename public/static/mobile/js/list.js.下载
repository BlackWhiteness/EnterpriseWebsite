
var select_wrapper = function(){
    /*筛选公共点击事件*/
    // $(".grade-eject, .sort-eject, .brand-eject, .more-eject, .type-eject").height(window.innerHeight*0.6);
    $(".selectWrap li").click(function(){
        var selWrap = $(".selectWrap").siblings(".eject").children().eq($(this).index());
        // alert(selWrap)
        if(!selWrap.hasClass('grade-w-roll')){
            selWrap.siblings().removeClass('grade-w-roll');
            selWrap.siblings().hide();
            selWrap.show();
            $(this).addClass('current').siblings().removeClass('current');
            $('.screenTabBox .selectWrap,.selectWrapBox .selectWrap').attr('style','position: fixed;top:0;left: 0;z-index:1006;');
            $(".screenTabBox ,selectWrapBox").attr('style','z-index:1001;');
            setTimeout(function(){
                selWrap.addClass('grade-w-roll');
            },50)
            $("body").addClass('modal-open');
        }else{
            selWrap.removeClass('grade-w-roll');
            selWrap.hide();
            $('.screenTabBox .selectWrap,.selectWrapBox .selectWrap').attr('style','');
            $(this).removeClass('current');
            $("body").removeClass("modal-open");
        }

    });

    //地区省市筛选
    $(".regSelect").click(function(){
        // $(".grade-t li").eq(0).find("a").addClass("focus");
        var lilength = $(".grade-w").find("li a.focus").length;//市
        var qulength = $(".grade-t").find("li a.focus").length;//区
        var zhenglength = $(".grade-a").find("li a.focus").length;//镇

        if(lilength > 0){
            $(".grade-t").eq($(this).parent("li").index()).css({"left":"33.333%"});

            if(qulength==0){
                $(".grade-t").css({"left":"100%"});
            }
        }else{
            $(".grade-t").css({"left":"100%"});
        }


        if(qulength > 0){
            $(".grade-a").eq($(this).index()).css({"left":"66.666%"});
            if(zhenglength==0){
                $(".grade-a").css({"left":"100%"});
            }
        }else{
            $(".grade-a").css({"left":"100%"});
        }

        if(zhenglength > 0){
            $(".grade-a").eq($(this).index()).css({"left":"66.666%"});
            if(qulength==0){
                $(".grade-a").eq($(this).index()).css({"left":"100%"});
            }
        }else{
            $(".grade-a").css({"left":"100%"});
        }

    });
    /*省*/
    $(".grade-w li").click(function(){

        $(".grade-t").css({"left":"33.333%"});
        $(this).find("a").addClass("focus");
        $(this).siblings("li").find("a").removeClass("focus");

        $(".grade-t li").eq($(this).index()).show();
        $(".grade-t li").eq($(this).index()).siblings().hide();
        $(".grade-a").css({"left":"100%"});

        // var choose = $(this).text();
        // $(this).parents(".eject").siblings(".selectWrap").find(".regSelect").html("<span class='focus'>"+choose+"<i></i></span>");
        var type = $('#gradew').attr('type');
        if(type == 'land'){
            var ynul = $(".grade-t li").find("a").length;
        }else{
            var ynul = $(".grade-t li").eq($(this).index()).find("a").length;
        }
        if(ynul == 0){
            var choose = $(this).text();
            $(this).parents(".eject").siblings(".selectWrap").find(".regSelect").html("<span class='focus'>"+choose+"<i></i></span>");
            $(this).parents(".grade-eject").hide();
            $('.grade-eject').removeClass('grade-w-roll');
            $(this).parents(".grade-w").siblings(".grade-t").css({"left":"100%"});

            $('.selectWrap').attr('style','');
            $(".regSelect").removeClass('current');
            $(".newsListBox").removeClass("modal-open");
            $(".grade-eject > ul.grade-a li a").removeClass("focus");
            $(".grade-eject > ul.grade-t li a.focus").removeClass("focus");
        }else{
            $(this).parent(".grade-w").siblings(".grade-t").css({"left":"33.333%"});
            $(".grade-a").css({"left":"100%"});
            cityclick();
            event.stopPropagation();
            return false;
        };


    });
    cityclick();
    function cityclick(){
        /*市*/
        $(".grade-t>li a").click(function(){
            var thisNum = $(this).parent("li").index();
            $(".grade-a > li").eq(thisNum-1).show();
            $(".grade-a > li").eq(thisNum-1).siblings().hide();
            $(".grade-a > li").eq(thisNum-1).find("ul.qu li").eq($(this).index()).show();
            $(".grade-a > li").eq(thisNum-1).find("ul.qu li").eq($(this).index()).siblings().hide();
            $(this).addClass("focus").siblings("a").removeClass("focus").parents("li").siblings("li").find("a").removeClass("focus");
            //$(".grade-a > li").removeClass()
            $(".grade-a > li ul.qu li").find("a").removeClass("focus");
            // var choose = $(this).text();
            // $(this).parents(".eject").siblings(".selectWrap").find(".regSelect").html("<span class='focus'>"+choose+"<i></i></span>");
            var ynul =  $(".grade-a > li").eq(thisNum-1).find("ul.qu li").eq($(this).index()).find("a").length;

            if(ynul == 0){
                var choose = $(this).text();
                $(this).parents(".eject").siblings(".selectWrap").find(".regSelect").html("<span class='focus'>"+choose+"<i></i></span>");
                $(this).parents(".grade-eject").hide();
                //$(this).parents(".grade-t").css({"left":"100%"});
                $('.grade-eject').removeClass('grade-w-roll');
                $('.selectWrap').attr('style','');
                $(".regSelect").removeClass('current');
                $(".newsListBox").removeClass("modal-open");
                $(".grade-a").css({"left":"100%"});
                $(".grade-eject > ul.grade-a li a").removeClass("focus");
                // $(".grade-eject > ul.grade-t li a.focus").removeClass("focus");

            }else{
                $(".grade-a").css({"left":"66.666%"});
                areaclick();
                event.stopPropagation();
                return false;
            }
        });
        areaclick();
    }
    function areaclick(){
        /*区*/
        $(".grade-a>li a").click(function(){
            $(this).addClass("focus").siblings("a").removeClass("focus").parents("li").siblings("li").find("a").removeClass("focus");
            var choose = $(this).text();
            $(this).parents(".eject").siblings(".selectWrap").find(".regSelect").html("<span class='focus'>"+choose+"<i></i></span>");
            $(this).parents(".grade-eject").hide();
            //$(this).parents(".grade-a").css({"left":"100%"});
            $('.grade-eject').removeClass('grade-w-roll');
            $('.selectWrap').attr('style','');
            $(".regSelect").removeClass('current');
            $(".newsListBox").removeClass("modal-open");
            event.stopPropagation();
        })
    }


    /*面积*/
    $(".one-eject li").click(function(){
        var choose = $(this).text();
        var one_txt = $(this).parents(".eject").siblings(".selectWrap").children("ul").children("li").eq($(this).parent().parent().index());
        $(this).find("a").addClass("focus");
        $(this).siblings("li").find("a").removeClass("focus");
        one_txt.html("<span class='focus'>"+choose+"<i></i></span>");
        one_txt.removeClass('current');
        $('.screenTabBox .selectWrap').attr('style','');
        $('.select_wrapper').attr('style','');
        $("body").removeClass("modal-open");
        $('.one-eject').removeClass('grade-w-roll');
        $(this).parents(".one-eject").hide();
    });

    /*价格*/
    $(".two-eject li").click(function(){
        var choose = $(this).text();
        var one_txt = $(this).parents(".eject").siblings(".selectWrap").children("ul").children("li").eq($(this).parent().parent().index());
        $(this).find("a").addClass("focus");
        $(this).siblings("li").find("a").removeClass("focus");
        one_txt.html("<span class='focus'>"+choose+"<i></i></span>");
        one_txt.removeClass('current');
        $('.screenTabBox .selectWrap').attr('style','');
        $('.select_wrapper').attr('style','');
        $("body").removeClass("modal-open");
        $('.two-eject').removeClass('grade-w-roll');
        $(this).parents(".two-eject").hide();
    });
    /*更多筛选事件*/
    var MoreSpan = $(".more-eject>dl>dd div span");
    var MoreRes = $(".more_sub_con .more_reset");
    var MoreSub = $(".more_sub_con .more_sub")
    MoreSpan.click(function(){
        $(this).addClass("focus").siblings().removeClass("focus");
    });
    MoreRes.click(function(){
        MoreSpan.removeClass("focus");
    });
    MoreSub.click(function(){
        var ejectChild =$(".eject").children().eq($(this).index());
        ejectChild.siblings("div").removeClass('grade-w-roll');
        ejectChild.siblings("div").css("display","none");
        $('.screenTabBox .selectWrap').attr('style','');
        $('.select_wrapper').attr('style','');
        $("body").removeClass("modal-open");
        $(".more_select,.moreSelect").removeClass('current');
    });

    var inp = $(".brand-eject .brand_select_form input");
    if(inp.length!=0){
        inp[0].addEventListener('focus',function(){
            if(!this.value.trim() || this.value.trim()=="最小面积"){
                this.value ="";
            }
        });
        inp[0].addEventListener('blur',function(){
            if(!this.value.trim() || this.value.trim()=="最小面积"){
                this.value ="";
            }
        });
        inp[1].addEventListener('focus',function(){
            if(!this.value.trim() || this.value.trim()=="最大面积"){
                this.value ="";
            }
        });
        inp[1].addEventListener('blur',function(){
            if(!this.value.trim() || this.value.trim()=="最大面积"){
                this.value ="";
            }
        });
        inp[3].addEventListener('focus',function(){
            if(!this.value.trim() || this.value.trim()=="最小价格"){
                this.value ="";
            }
        });
        inp[3].addEventListener('blur',function(){
            if(!this.value.trim() || this.value.trim()=="最小价格"){
                this.value ="";
            }
        });
        inp[4].addEventListener('focus',function(){
            if(!this.value.trim() || this.value.trim()=="最大价格"){
                this.value ="";
            }
        });
        inp[4].addEventListener('blur',function(){
            if(!this.value.trim() || this.value.trim()=="最价格"){
                this.value ="";
            }
        });
    }

    /*遮罩层关闭*/
    var MaskFix = $(".screenTabBox  .mask_fix,.selectWrapBox  .mask_fix");
    MaskFix.click(function(){
        var ejectChild =$(".eject").children();
        ejectChild.siblings("div").removeClass('grade-w-roll');
        ejectChild.css("display","none");
        $('.screenTabBox .selectWrap,.selectWrapBox  .selectWrap').attr('style','');
        $('.select_wrapper').attr('style','');
        $("body").removeClass("modal-open");
        $('.select_wrapper li,.screenTabBox .selectWrap li').removeClass('current');
    });
}

/*调用参数*/
function msg(type,total){
    var ProInfo = document.getElementById("prompt_info");
    var total = arguments[1] ? arguments[1] : 0;//设置第一个参数的默认值为0
    var type = arguments[0] ? arguments[0] : 1;//设置第二个参数的默认值为1
    if(ProInfo!=null || Boolean(ProInfo)){
        if(type==1){
            ProInfo.innerHTML = '当前共有<span>'+total+'</span>条信息';
        }else if(type == 2){
            ProInfo.innerHTML = '您目前所在城市没有信息，已为您推荐其他城市的土地';
        }else if(type == 3){
            ProInfo.innerHTML = '没有符合条件的信息，请修改条件或关键词';
        }else if(type == 4){
            ProInfo.innerHTML = '当前城市尚未开通城市站，请移至顶部切换';
        }
        //提示信息3s消失
        setTimeout(function(){
            $("#promptInfo,#prompt_info").text('').css({"height":"0.2rem","line-height":"0.2rem"});
        },3000);//3秒后消失
    }
}
var count=$('#prompt_info').attr('count');
msg(1,count);


/********************* 筛选标签溢出宽度 **********************/
var screenTag = function(){
    var screenTagCon  =$(".screenTag .screenTagCon");
    var screenTagSpan  =$(".screenTag .screenTagCon").find('span');
    var screenTagWid=0;
    for(var i=0;i<screenTagSpan.length;i++){
        screenTagWid += screenTagSpan.eq(i).width();
    }
    var bodyWid = $(window).width();
    if(screenTagCon.length!=0 || Boolean(screenTagCon)){
        var Mr = $(".screenTag .screenTagCon").find('span').css('margin-right');
        var rWid = (Mr.replace('px',""))*(screenTagSpan.length)
        screenTagCon.css({"width":rWid+screenTagWid+2});
        screenTagSpan.tap(function(){
            if($(this).hasClass("focus")){
                $(this).removeClass("focus")
            }else{
                $(this).addClass("focus")
            }
        })
    }
}

/********************* 头部滑动 **********************/
var screenFix = function(){
    var screenTabBoxTop = $(".screenTabBox,.AselectWrapBox").offset().top;
    $(window).scroll(function(){

        var winTop=$(window).scrollTop() || document.documentElement.scrollTop || document.body.scrollTop;
        if(winTop>screenTabBoxTop){
            $(".screenBox,.AselectWrapBox").addClass("screenBoxFix")
        }else{
            $(".screenBox,.AselectWrapBox").removeClass("screenBoxFix")
        }
    })
}