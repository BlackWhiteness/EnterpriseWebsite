fontSize();
$(window).resize(function () {
    fontSize();
});

function fontSize() {
    var size;
    var winW = $(window).width();
    if (winW <= 750) {
        size = Math.round(winW / 7.5);
        if (size > 65) {
            size = 65;
        }
    } else {
        size = 100;
    }
    $('html').css({
        'font-size': size + 'px'
    })
}

$(function () {
    //--
    setTimeout(function () {
        $('body').addClass('show');
    }, 500);
    //--
    $('.navA').click(function () {
        if ($('body').hasClass('navShow')) {
            $('body').removeClass('navShow')
        } else {
            $('body').addClass('navShow')
        }
    });
    $('.g-nav').find('li').each(function () {
        var _ = $(this);
        if ($(this).find('.list').length > 0) {
            _.find('a.name').click(function () {
                if ($(window).width() > 800) return;
                if (_.hasClass('on')) {
                    _.removeClass('on');
                    _.find('.list').hide();
                } else {
                    _.addClass('on');
                    _.find('.list').show();
                }
                return false;
            })
        }
    });
    //--返回顶部
    scroll2top();

    //--js下拉选择框
    $('.select').each(function () {
        var _this = $(this);
        _this.find('select').change(function () {
            _this.find('span').html($(this).find("option:selected").text());
        })
    });
    p_class_fun();

    //--导航
    $('.g-head .nav').click(function () {
        $('.g-nav').addClass('show');
        $('body').addClass('noScroll')
    });
    $('.g-nav .close').click(function () {
        $('.g-nav').removeClass('show');
        $('body').removeClass('noScroll')
    });

    //--搜索
    $('.index-search .search').click(function () {
        $('.g-search').addClass('show');
        $('body').addClass('noScroll')
    });
    $('.g-head .search').click(function () {
        $('.g-search').addClass('show');
        $('body').addClass('noScroll')
    });
    $('.g-search .close').click(function () {
        $('.g-search').removeClass('show');
        $('body').removeClass('noScroll')
    });
    $('.g-search').find('.input input').keyup(function () {
        if($(this).val() !== ""){
            $('.g-search .search-list').addClass('show')
        }else{
            $('.g-search .search-list').removeClass('show')
        }
    });
    tabFun({dom: $('.g-search'), curr: 0})

});

function p_class_fun() {
    var dom = $('.p-class-d'),
        classList = dom.find('.p-class'),
        dropDown = dom.find('.drop-down');
    if(dom.length === 0) return;
    $(window).scroll(function () {
        if($(window).scrollTop() > dom.offset().top){
            classList.addClass('on')
        }else{
            classList.removeClass('on')
        }
    });
    classList.find('li').each(function (i) {
        $(this).click(function () {
            if($(this).hasClass('open')){
                drop_hide()
            }else{
                drop_show(i)
            }
        })
    });
    dom.find('.mask_fix').click(function () {
        drop_hide()
    });
    function drop_show(i) {
        drop_hide();
        classList.find('li').eq(i).addClass('open');
        dropDown.eq(i).addClass('show');
        dom.addClass('on');
        $('body').addClass('noScroll')
    }
    function drop_hide() {
        dom.removeClass('on');
        classList.find('li').removeClass('open');
        dropDown.removeClass('show');
        $('body').removeClass('noScroll')
    }

    var area = dom.find('.drop-area');
    area.find('.list-1 li').each(function (i) {
        $(this).click(function () {
            area.find('.list-1 li').removeClass('on');
            $(this).addClass('on');
            area.find('.list-2').addClass('show');
            area.find('.list-2').find('ul').hide();
            area.find('.list-2').find('ul').eq(i-1).show()
        })
    });

    dom.find('.drop-list').each(function () {
        var li = $(this).find('li');
        li.click(function () {
            li.find('a').removeClass('on');
            $(this).find('a').addClass('on')
        })
    });

    var dom_selected = $('.p-class-selected');
    dom_selected.find('ul').addClass('swiper-wrapper');
    dom_selected.find('li').addClass('swiper-slide');
    new Swiper(dom_selected, {
        loop:false,
        autoplay: false,
        autoplayDisableOnInteraction: false,
        paginationClickable: true,
        speed: 600,
        slidesPerView: 'auto'
    })
}

function swiperFun(swiper) {
    this.dom = swiper.dom;
    this.domList  = this.dom;
    this.dom.find('ul').addClass('swiper-wrapper');
    this.dom.find('li').addClass('swiper-slide');
    if(swiper.domList !== undefined){
        this.domList = this.dom.find(swiper.domList)
    }
    if(this.dom.find('.num').length > 0){
        this.dom.find('.num-total').html(this.dom.find('li').length)
    }

    this.change = function () {};
    var that = this;
    this.mySwiper = new Swiper(that.domList, {
        loop:true,
        autoplay: 5000,
        autoplayDisableOnInteraction: false,
        paginationClickable: true,
        speed: 600,
        slidesPerView: swiper.slidesPerView !== undefined ? swiper.slidesPerView : 1,
        centeredSlides: swiper.centeredSlides !== undefined ? swiper.centeredSlides : false,
        pagination: that.dom.find('.dots'),
        onSlideChangeStart: function(swiper){
            if(that.dom.find('.num').length > 0){
                that.dom.find('.num-curr').html(swiper.realIndex + 1)
            }
            that.change(swiper.realIndex);
        }
    });
    this.dom.hover(
        function () {
            that.mySwiper.stopAutoplay()
        },
        function () {
            that.mySwiper.startAutoplay()
        }
    );
    this.dom.find('.prev').click(function () {
        that.mySwiper.slidePrev()
    });
    this.dom.find('.next').click(function () {
        that.mySwiper.slideNext()
    })
}

//--选项卡-- tabFun({dom: $('.about'), curr: 0});
function tabFun(tab) {
    var btn = tab.dom.find('.tab-btn li'),
        box = tab.dom.find('.tab-box');

    btn.each(function (i) {
        $(this).click(function () {
            change(i)
        })
    });

    change(tab.curr);
    function change(curr) {
        btn.removeClass('on');
        btn.eq(curr).addClass('on');
        box.hide();
        box.eq(curr).fadeIn()
    }
}

function scroll2top() {
    var btn = $('.topA');
    btn.click(function () {
        $('body,html').stop(true, true).animate({scrollTop: 0}, 300);
    });
    $(window).scroll(function () {
        if($(window).scrollTop() > $(window).height()){
            btn.addClass('show')
        }else{
            btn.removeClass('show')
        }
    });
}
