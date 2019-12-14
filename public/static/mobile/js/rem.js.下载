(function(){
    var timer = '';
    function changeRem(){
        var deviceWidth = document.documentElement.clientWidth;//获取设备的宽度
        if(deviceWidth > 750){
            deviceWidth = 750;
        }
        document.documentElement.style.fontSize = deviceWidth/7.5+'px';
    }
    //监听页面的宽度的大小
    window.addEventListener('resize',function(){
        clearInterval(timer);
        timer = setTimeout(changeRem,30);
    },false);
    //页面加载的时候，若果是调用缓存的话就再执行changeRem
    window.addEventListener("pageshow",function(e){
        if (e.persisted)//缓存
        {
            clearTimeout(timer);
            timer = setTimeout(changeRem,30);
        }
    },false);

    changeRem();
})();/**
 * Created by tx on 2017/5/19.
 */

var rheadBox2 = document.querySelector(".headBox");
console.log(rheadBox2)
// var rheadBoxH = rheadBox2.offsetHeight;
// if(Boolean(rheadBox)){
//     var div = '<div class="headSeat" style="height:'+rheadBoxH+'px">ss</div>';
//     // rheadBox.after(div);
// }
