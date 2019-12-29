var search_title = false;
var search_title_all = false;
var area = '', city = '', measurearea = '', rent = '', mianji = '', title = '', tag = ''
type = '';

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
    } else {
        area = '';
    }

    action();
});


$("#rent a").click(function () {
    var position = $("#rent a").index(this);
    $("#rent a").attr("class", "");
    $("#rent a").eq(position).attr("class", 'current');
    rent = $("#rent a").eq(position).attr('value');
    action();
});
$("#tag a").click(function () {
    var position = $("#tag a").index(this);
    $("#tag a").attr("class", "");
    $("#tag a").eq(position).attr("class", 'current');
    tag = $("#tag a").eq(position).attr('value');
    action();
});
$("#type a").click(function () {
    var position = $("#type a").index(this);
    $("#type a").attr("class", "");
    $("#type a").eq(position).attr("class", 'current');
    type = $("#type a").eq(position).attr('value');
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

function action(current_page = 1) {
    var data = {}, city = $("#city_id").attr('value');

    if (city != 0) {
        data.city = city;
    }
    if (area != "" && !search_title_all) {
        data.area = area;
    }
    if (rent != '') {
        data.rent = rent;
    }

    if (mianji != 0) {
        data.measurearea = mianji;
    }
    if (tag != '') {
        data.tag = tag;
    }
    if (type != '') {
        data.type = type;
    }
    if ((search_title_all || search_title) && title != '') {
        data.title = title;
    }
    data.page = current_page;
    $.ajax({
        type: "POST",
        url: "/search_land",
        data: data,
        success: function (result) {
            $(".right_cont").empty();
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
        jump: false,
        callback: function (api) {
            action(api.getCurrent());
        }
    });
}


//渲染html
function resultFilter(result) {
    // $.each(result.data, function (index, row) {
    //     var html = '';
    //     html += "<a href=/index/search/landdetail?id=" + row.id + " target='_blank'>";
    //     html += "<div class='list-item' link=" + "/index/search/landdetail?id=" + row.id + "> ";
    //     html += "<img src=" + row.imgs + " alt=" + row.title + " class=\"item-img\" width=\"150\" height=\"115\"/> ";
    //     html += "<dl class=\"item-info\">";
    //
    //     html += "<dt class=\"item-title\" title=" + row.title + ">" + row.title;
    //     html += "<dd>" + "<span>" + row.title + "</span>";
    //     html += "<i class='line'>|</i>";
    //     html += "<span>" + row.title + "</span>" + "</dd>";
    //     html += "<dd class='measurearea'><span>" + row.measurearea + "</span></dd>";
    //     html += "<dd class='address'><span>" + row.address + "</span></dd></dl>"
    //     html += "<div class='item-price'><div class='price-a'>";
    //     html += "<em>" + row.price + "</em>元/m&sup2;•月</div>";
    //     html += "<span class='price-b'>" + row.price + "</span></div></div></a>";
    //
    //     $("#list").append(html);
    // });
    $.each(result.data, function (index, row) {
        var html = '';
        html += "<div class='list_one'>";
        html += "<div class='list_one_img'>";
        html += "<a href=/index/search/landdetail?id=" + row.id + " target='_blank' title=" + row.title + ">";
        html += "<img src=" + row.imgs + " alt=" + row.title + " border='0' width='146' height='130'/></a> ";
        html += "<span class='imgmid'></span></div><div class='list_one_text'> <strong>";
        html += "<a href=/index/search/landdetail?id=" + row.id +  "target='_blank'>";
        html += row.title + "</a></strong>";
        html += "<div class='cf_jianjie'>" + row.detail + "</div>";
        html += "<div class='text_prm'><span>联系人:<font style='color: #C60;font-weight:bold;'>" + row.name + "</font><img src='/static/index/img/jjr_rz.jpg'/></span>";
        html += "<span>联系方式:<font " + "style='color: #933;font-weight:bold;background: url(img/list2_icopho.jpg) no-repeat left center; padding-left:11px;'>";
        html += row.tel + "</font></span>";
        html += "</div> <div class='text_prm1' style='display:none'>";
        // html += "<span style='border-color: #330;'>" + row.tag + "</span>";
        html += "</div></div>" + "<div class='list_one_prm'>" +
            "<p style='font-size:15px; font-weight:600;'>" + row.area_name + "</p>" +
            "<p><span class='f16' style='color:#53160F;'>" + row.price + "</span>元/㎡</p>" +
            "<p>面积:<span class='f16'>" + row.measurearea + "</span>㎡</p>";
        html += "<a class='cf_ckxq' href=/index/search/landdetail?id=" + row.id + " target='_blank'>查看详情</a>" + "</div></div>"
        $(".right_cont").append(html);
    });
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    console.log(r);
    if (r != null) return unescape(r[2]);
    return null;
}