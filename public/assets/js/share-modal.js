(function () {
    "use strict";

    // جلب زر المشاركة والمودال بشكل آمن
    var shareBtn = document.getElementById('shareBtn'); // أو الاسم المستخدم في قالبك
    
    // التحقق من وجود العنصر في الصفحة الحالية قبل إضافة الـ Event Listener
    if (shareBtn) {
        shareBtn.addEventListener('click', function (event) {
            event.preventDefault();
            // كود تشغيل المودال الأصلي الخاص بك هنا..
            if (typeof $.fn.modal !== 'undefined') {
                $('#share-modal').modal('show');
            }
        });
    }

})();