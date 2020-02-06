/**
 * Created by xiaodongpan on 2017/4/28.
 */
(function(win, $, undefined) {

    var UTILS = (function() {
        return {
            //loader
            load: function(url, params, callback, errcallback) {
                var errcallback = errcallback || $.noop,
                    setting = {
                        url: url + '?' + $.param(params),
                        cache: false,
                        dataType: 'jsonp',
                        jsonp: params.cb || 'cb',
                        timeout: 30000,
                        success: function() {
                            if (callback && typeof(callback) == 'function') {
                                callback.apply(null, arguments);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            window.console && console.log && console.log(textStatus);
                            errcallback();
                        }
                    };
                $.ajax(setting);
            },

            /*
             * set cookie
             * @params : cookie name, value, time
             */
            setCookie: function(c_name, value, expiredays) {
                var exdate = new Date();
                exdate.setDate(exdate.getDate() + expiredays);
                document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
            },

            /*
             * get cookie
             * @params : cookie name
             */
            getCookie: function(c_name) {
                if (document.cookie.length > 0) {
                    var c_start = document.cookie.indexOf(c_name + "=");
                    if (c_start != -1) {
                        c_start = c_start + c_name.length + 1;
                        c_end = document.cookie.indexOf(";", c_start);
                        if (c_end == -1) c_end = document.cookie.length;
                        return decodeURIComponent(document.cookie.substring(c_start, c_end));
                    }
                }
                return;
            },

            //函数节流
            throttle: function(fn, context, delay, text, mustApplyTime){
                fn.timer && clearTimeout(fn.timer);
                fn._cur = Date.now(); //记录当前时间

                if(!fn._start){
                    //若该函数是第一次调用，则直接设置_start,即开始时间，为_cur，即此刻的时间
                    fn._start = fn._cur;
                }

                if( fn._cur - fn._start > mustApplyTime ){
                    //当前时间与上一次函数被执行的时间作差，与mustApplyTime比较，若大于，则必须执行一次函数，若小于，则重新设置计时器
                    fn.call( context, text );
                    fn._start = fn._cur;
                } else {
                    fn.timer = setTimeout(function(){
                        fn.call( context, text );
                    }, delay);
                }
            }
        }
    })();
    win.UTILS = UTILS;
})(this, jQuery);