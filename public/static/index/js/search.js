$(function () {
    action(1);
});

//处理点击事件
$("#mianji a").click(function () {
    var position = $("#mianji a").index(this);
    $("#mianji a").attr("class", "");
    $("#mianji a").eq(position).attr("class", 'current');
    action();
});

$(".screen_area a").click(function () {
    var position = $(".screen_area a").index(this);
    $(".screen_area a").attr("class", "");
    $(".screen_area a").eq(position).attr("class", 'current');
    action();
});

$("#floor a").click(function () {
    var position = $("#floor a").index(this);
    $("#floor a").attr("class", "");
    $("#floor a").eq(position).attr("class", 'current')
    action();
});
$("#struck a").click(function () {
    var position = $("#struck a").index(this);
    $("#struck a").attr("class", "");
    $("#struck a").eq(position).attr("class", 'current');
    action();
});

$(function () {
    $("#title_click").click(function () {
        var title = $("#title_input").val();
        action(title);
    });
    $("#title_click_all").click(function () {
        var title = $("#title_input").val();
        action(title, true)
    });
});

function action(current_page) {
// function action(title = "", all = false, page) {
    //screen_area
    //city_id
    var measurearea = $("#mianji .current").attr('value'),
        floor = $("#floor .current").attr('value'),
        structure = $("#struck .current").attr('value'),
        city = $("#city_id").attr('value'),
        area = $(".screen_area .current").attr('value'),
        data = {};
    var scree_position = $('.screen_area .current').index();
    if (city != 0) {
        data.city = city;
    }
    // if (title != "") {
    //     data.title = title;
    // }
    // if (!all && scree_position != 0) {
    //     data.area = area;
    // }
    if (measurearea != 0) {
        data.measurearea = measurearea;
    }
    if (floor != 0) {
        data.floor = floor;
    }
    if (structure != 0) {
        data.structure = structure;
    }
    data.page = current_page;
    // data.page = page;
    $.ajax({
        type: "POST",
        url: "/ajax_search_ws",
        data: data,
        success: function (result) {
            $(".right_cont").empty();
            resultFilter(result);
            pageinit(result.page.total,result.page.current_page,result.page.last_page)
        },
        error:function(result){
            alert('数据加载失败！');
        }
    });
}

function pageinit(total,current,last_page){
    $('#page').pagination({
        pageCount: total,
        current:current,
        pageCount:last_page,
        showData:1,
        jump: true,
        callback: function (api) {
            // var data = {
            //     page: api.getCurrent(),
            //     name: 'mss',
            //     say: 'oh'
            // };

            action( api.getCurrent());
        }
    });
}


function offlinePageCallback(page) {
    // var pageNum = page_index+1;
    // page_index++;
    // if (page_index != 1) {
    $('#pageIndex').val(page+1);
        action(page);
    // }
    // console.log(page_index);
    return false;
}

//渲染html
function resultFilter(result) {
    $.each(result.data, function (index, row) {
        var html = '';
        html += "<div class='list_one'>";
        html += "<div class='list_one_img'>";
        html += "<a href=/index/search/workshopdetail?id=" + row.id + " target='_blank' title=" + row.title + ">";
        html += "<img src=" + row.imgs + " alt=" + row.title + " border='0' width='146' height='130'/></a> ";
        html += "<span class='imgmid'></span></div><div class='list_one_text'> <strong>";
        html += "<a href=/index/search/workshopdetail?id=" + row.id + " target='_blank'>";
        html += row.title + "</a></strong>";
        html += "<div class='cf_jianjie'>" + row.title + "</div>";
        html += "<div class='text_prm'><span>联系人:<font style='color: #C60;font-weight:bold;'>" + row.name + "</font><img src='/static/index/img/jjr_rz.jpg'/></span>";
        html += "<span>联系方式:<font " + "style='color: #933;font-weight:bold;background: url(img/list2_icopho.jpg) no-repeat left center; padding-left:11px;'>";
        html += row.tel + "</font></span>" + "<span>发布时间:<font style='color: #639;font-weight:bold;'>";
        html += row.releasetime + "</font></span>" + "</div> <div class='text_prm1' style='display:none'>";
        html += "<span style='border-color: #330;'>" + row.tag + "</span>";
        // "<span style='border-color: #C30;'>提供租赁合同</span>" +
        // "<span style='border-color: #660;'>消防验收合格</span>" +
        html += "</div></div>" + "<div class='list_one_prm'>" +
            "<p style='font-size:15px; font-weight:600;'>" + row.area_name + "</p>" +
            "<p><span class='f16' style='color:#53160F;'>" + row.plantrent + "</span>元/㎡</p>" +
            "<p>面积:<span class='f16'>" + row.measurearea + "</span>㎡</p>";
        html += "<a class='cf_ckxq' href=/index/search/workshopdetail?id=" + row.id + " target='_blank'>查看详情</a>" + "</div></div>"
        $(".right_cont").append(html);
    });
    return true;
}