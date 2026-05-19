(function($) {
    "use strict";
    
    //P-scrolling
    
    // تحقق أولاً من وجود عنصر الشات في الصفحة قبل تشغيل السكرول
    if (document.querySelector('.chat-scroll')) {
        const ps2 = new PerfectScrollbar('.chat-scroll', {
          useBothWheelAxes: true,
          suppressScrollX: true,
        });
    }

    // تحقق أولاً من وجود عنصر الإشعارات في الصفحة قبل تشغيل السكرول
    if (document.querySelector('.Notification-scroll')) {
        const ps3 = new PerfectScrollbar('.Notification-scroll', {
          useBothWheelAxes: true,
          suppressScrollX: true,
        });
    }
    
})(jQuery);