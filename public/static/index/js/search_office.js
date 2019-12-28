var search_title = false;
var search_title_all = false;
var area = '', city = '', measurearea = '', rent = '', mianji = '', title = '';
var indus_type = '';
var of_tag = '';
var floor = '';

function initOnclickTitle() {
    search_title = false;
    search_title_all = false;
}

$(function () {
    let urlTitle = getQueryString('title');
    if (title == '' && urlTitle) {
        title = urlTitle;
        $("input[name='title']").val(urlTitle);
    }
    action(1);
});

$(".searanniu").click(function () {
    let topTitle = $("#top_search").val();
    search_title = true;
    if(topTitle){
        title = topTitle;
        action();
    }else{
        title = '';
        action();
    }
});

//处理点击事件
$("#mianji a").click(function () {
    var position = $("#mianji a").index(this);
    $("#mianji a").attr("class", "");
    $("#mianji a").eq(position).attr("class", 'current');
    mianji = $("#mianji a").eq(position).attr('value');
    action();
});

$(".screen_area a").click(function () {
    var position = $(".screen_area a").index(this);
    $(".screen_area a").attr("class", "");
    $(".screen_area a").eq(position).attr("class", 'current');
    if (position != 0) {
        area = $(".screen_area a").eq(position).attr('value');
    }else{
        area='';
    }

    action();
});

$("#rent a").click(function () {
    var position = $("#rent a").index(this);
    $("#rent a").attr("class", "");
    $("#rent a").eq(position).attr("class", 'current');
    rent = $("#floor a").eq(position).attr('value');
    action();
});


$(function () {
    $("#title_click").click(function () {
        title = $("#title_input").val();
        search_title_all = false;
        search_title = true;
        action();
    });
    $("#title_click_all").click(function () {
        title = $("#title_input").val();
        search_title = false;
        search_title_all = true;
        action()
    });
});

$("#type a").click(function () {
    let position = $("#type a").index(this);
    $("#type a").attr("class", "");
    $("#type a").eq(position).attr("class", 'current');
    indus_type = $("#type a").eq(position).attr('value');
    action();
});
$("#tag a").click(function () {
    let position = $("#tag a").index(this);
    $("#tag a").attr("class", "");
    $("#tag a").eq(position).attr("class", 'current');
    of_tag = $("#tag a").eq(position).attr('value');
    action();
});
$("#floor a").click(function () {
    let position = $("#floor a").index(this);
    $("#floor a").attr("class", "");
    $("#floor a").eq(position).attr("class", 'current');
    floor = $("#floor a").eq(position).attr('value');
    action();
});

function action(current_page = 1) {
    var data = {}, city = $("#city_id").attr('value');

    if (city != 0) {
        data.city = city;
    }
    if (area != "" && !search_title_all) {
        data.area = area;
    }

    if (mianji != 0) {
        data.measurearea = mianji;
    }

    if ((search_title_all || search_title) && title != '') {
        data.title = title;
    }

    if (indus_type != 0) {
        data.indus_type = indus_type;
    }
    if (of_tag != 0) {
        data.tag = of_tag;
    }
    if (floor != 0) {
        data.floor_type = floor;
    }
    data.page = current_page;
    $.ajax({
        type: "POST",
        url: "/search_of",
        data: data,
        success: function (result) {
            $(".list-content").empty();
            resultFilter(result);
            pageinit(result.page.total, result.page.current_page, result.page.last_page)
        },
        error: function (result) {
            alert('数据加载失败！');
        }
    });
}

function pageinit(total, current, last_page) {
    $('#page').pagination({
        pageCount: total,
        current: current,
        pageCount: last_page,
        showData: 20,
        jump: true,
        callback: function (api) {
            action(api.getCurrent());
        }
    });
}


//渲染html
function resultFilter(result) {
    var top = "";
    top += "<div class='list-sort'>";
    top += "<span class='tit'>" + "为您找到以下";
    top += "<em style=" + "float:none; font-weight:bold; font-size:15px;" + ">" + "深圳</em>写字楼出租</span>";
    $(".list-content").append(top);
    $.each(result.data, function (index, row) {
        var html = '';
        html += "<a href=/index/search/offbuilddetail?id=" + row.id + " target='_blank'>";
        html += "<div class='list-item' link=" + "/index/search/offbuilddetail?id=" + row.id + "> ";
        html += "<img src=" + row.imgs + " alt=" + row.title + " class=\"item-img\" width=\"150\" height=\"115\"/> ";
        html += "<dl class=\"item-info\">";

        html += "<dt class=\"item-title\" title=" + row.title + ">" + row.title;
        html += "<dd>" + "<span>" + row.buildingname + "</span>";
        html += "<i class='line'>|</i>";
        html += "<span>" + row.buildingname + "</span>" + "</dd>";
        html += "<dd class='measurearea'><span>" + row.measurearea + "</span></dd>";
        html += "<dd class='address'><span>" + row.address + "</span></dd></dl>"
        html += "<div class='item-price'><div class='price-a'>";
        html += "<em>" + row.managementfee + "</em>元/m&sup2;•月</div>";
        html += "<span class='price-b'>" + row.plantrent + "</span></div></div></a>";

        $(".list-content").append(html);
    });
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    console.log(r);
    if (r != null) return decodeURI(r[2]);
    return null;
}