var $tab=$(".demanEntryBox .tab p");

$tab.tap(function(){
    var i = $(this).index();
    console.log(i)
    $(this).addClass('focus').siblings().removeClass('focus');
    $(this).parent('.tab').siblings('.con').children('li').removeClass('focus').eq(i).addClass('focus');
})

var $listTab =$('.listBox2 .listTit p.rt a');
var $listWidth=$(".listBox2 .list").width();
var $list=$(".listBox2 .list");
var $listLength=$(".listBox2 .list").length;
// $(".listBox2 .listBoxCon").css({"width":$listWidth*$listLength,"height":$list.eq(0).height()});
$listTab.click(function(){
    var i = $(this).index();
    $(this).addClass('focus').siblings().removeClass('focus');
    $('.listBox2 .listBoxCon').children('.list').removeClass('focus').eq(i).addClass('focus');
    // $('.listBox2 .listBoxCon').css({'left':-i*$listWidth,'height':$list.eq(i).height()})

});