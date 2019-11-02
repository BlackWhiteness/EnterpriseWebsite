// JavaScript Document

var speed=50; //数字越大速度越慢

var tab=document.getElementById("demo");

var tab1=document.getElementById("demo1");

var tab2=document.getElementById("demo2");

tab2.innerHTML=tab1.innerHTML;

function Marquee(){

if(tab2.offsetWidth-tab.scrollLeft<=0)

tab.scrollLeft-=tab1.offsetWidth

else{

tab.scrollLeft++;

}

}

var MyMar=setInterval(Marquee,speed);

tab.onmouseover=function() {clearInterval(MyMar)};

tab.onmouseout=function() {MyMar=setInterval(Marquee,speed)};
//setcookie函数
function setcookie(c_name,value,expiredays){
   var date=new Date();
   var type = arguments[3] ? arguments[3] : null;//获取第二个参数的值
   if(type==null){
   date.setDate(date.getDate()+expiredays);}
   else{
   date.setTime(date.getTime() + Number(expiredays) * 60 * 1000);}//设置cookie的时间为分钟,其中1000毫秒等于1秒
   document.cookie=c_name+ "=" +encodeURIComponent(value)+((expiredays==null) ? "" : ";expires="+date.toGMTString());
  }//函数setcookie的结束分号