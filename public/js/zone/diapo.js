(function(){
    //initialize swiper when document ready
    if($('.diapo.zone .swiper-slide').length > 1)
    {
        var swiper = new Swiper('.diapo.zone .swiper-container', {
            direction: 'horizontal',
            loop: true,
            speed:500,
            slidesPerView: 1,
            spaceBetween: 0,
            centeredSlides: true,
            autoplay: {
                delay:5000,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"></span>';
                },
            },
            breakpoints: {
                1080: {
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,

                    },
                },
            }
        });
    }
    lazyInit();

    function lazyInit()
    {
        $('.diapo.zone .swiper-container').find('.photo').each(function () {
            if(LazyLoad.ImageObserver != null){
                LazyLoad.ImageObserver.observe($(this)[0]);
            }else{
                LazyLoad.lazyObjects[LazyLoad.lazyObjects.length] = $(this)[0];
            }
        });
    }
})()

