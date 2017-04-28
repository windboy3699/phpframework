/**
 * Created by xiaodongpan on 2017/4/28.
 */
(function( win, $, UTILS, undefined ){
    var $body = $('body');

    //[单选、复选、下拉框]默认选择
    ;(function(){
        /*
         * @params:
         *			dom: 操作对象
         * 			default: value or array (单选框、下拉框单个字符串值；复选框数组)
         */
        win.setDefault = function( obj ){
            var $dom = obj.dom || '';
            var _default = obj.default || ''; //type

            if( $dom.length > 0 ){
                var _type = $dom[0].type;
                var _length = $dom.length;

                switch( _type ){
                    case 'radio':
                        if( typeof(_default) != 'string' ) return;
                        for( var _i = 0; _i < _length; _i ++ ){
                            $dom.eq(_i).val() == _default && $dom.eq(_i).attr('checked', true);
                        }
                        break;

                    case 'checkbox':
                        if( Object.prototype.toString.call(_default).slice(8, -1) != 'Array' ) return;
                        for( var _i = 0; _i < _length; _i++ ){
                            for( var _a = 0, _len = _default.length; _a < _len; _a ++ ){
                                $dom.eq(_i).val() == _default[_a] && $dom.eq(_i).attr('checked', true);
                            }
                        }
                        break;

                    case 'select-one':
                        if( typeof(_default) != 'string' ) return;

                        var $option = $dom.find('option');
                        var _optLen = $option.length;

                        for( var _o = 0; _o < _optLen; _o++ ){
                            $option.eq(_o).val() == _default && $option.eq(_o).attr('selected', true);
                        }
                        break;
                }
            }
        }
    })();


    //添加配置项
    ;(function(){
        var controlRow = function( params ){
            var params = params || {};

            //容错
            if( !params.rowName || !params.addBtn || !params.delBtn || !params.item ){
                alert('错误提示: 缺少参数配置!');
                return;
            }

            var _parentName = '.' + params.rowName;
            var _tmpl = [];

            _tmpl.push('<div class="form-group '+ params.rowName +'"><label class="col-sm-2 control-label"></label>');

            var _i = 0;
            var _len = params.item.length;
            for( ; _i < _len; ++_i ){
                _tmpl.push('<div class="col-sm-1"><input type="text" name="'+ params.item[_i].name +'" placeholder="'+ params.item[_i].placeholder +'" value="" class="form-control"></div>');
            }
            _tmpl.push(		'<div class="col-sm-1 control-items">');
            _tmpl.push(			'<a href="javascript:;" title="添加" class="'+ params.addBtn +'">+</a>');
            _tmpl.push(			'<a href="javascript:;" title="删除" class="'+ params.delBtn +'">×</a>');
            _tmpl.push(		'</div>');
            _tmpl.push('</div>');

            //增加行
            $body.on('click', '.' + params.addBtn, function(e){
                var _this = $(this);
                var _parent = _this.closest( _parentName );

                _parent.after( _tmpl.join('') );
            });

            //删除行
            $body.on('click', '.' + params.delBtn, function(e){
                $(this).closest( _parentName ).remove();
            });
        };

        win.controlRow = controlRow;
    })();


    //ajax & form切换( post , btn )
    ;(function(){
        var
            $btn_get = $('.J_Do_Get'), //get形式按钮
            $btn_post = $('.J_Do_Post'); //post形式按钮

        //post btn
        $btn_post.off().on('click', function(e){
            var _this = $(this),
                _form = _this.closest('form');

            _form.off().submit(function(e){
                var _action = _form.attr('action');
                var _redirect = _form.attr('data-redirect');
                // var _item = _form.find('input');
                // var _params = [ '?' ];

                // for( var _i = 0, _len = _item.length; _i < _len; ++_i ){
                //     _params.push(
                //         _item.eq(_i).prop('name') + '=' + encodeURIComponent( _item.eq(_i).prop('value') ) + ( _i + 1 < _len ? '&' : '')
                //     );
                // }

                $.post( _action, _form.serialize(), function(d){
                    if( d && d.code == 0 ){
                        d.msg && alert( d.msg );
                        if( !!_redirect ){
                            window.location.href = _redirect;
                        }
                    } else {
                        d.msg && alert( d.msg );
                    }
                });
                // UTILS.load(_action + _params.join("") , {
                //     cb: 'cb'
                // }, function(d){
                //     if( d && d.code == 0 ){
                //         d.msg && alert( d.msg );
                //         if( !!_redirect ){
                //             window.location.href = _redirect;
                //         }
                //     } else {
                //         d.msg && alert( d.msg );
                //     }
                // });

                return false;
            });
        });

        //get btn
        $btn_get.off().on('click', function(e){
            var _this = $(this),
                _url = _this.attr('data-get-url'),
                _redirect = _this.attr('data-redirect');

            $.getJSON(_url, function(d){
                if( d && d.code == 0 ){
                    d.msg && alert( d.msg );
                    if( !!_redirect ){
                        window.location.href = _redirect;
                    }
                } else {
                    d.msg && alert(d.msg);
                }
            });
            // UTILS.load( _url, {
            //     cb: 'cb'
            // }, function(d){
            //     if( d && d.code == 0 ){
            //         d.msg && alert( d.msg );
            //         if( !!_redirect ){
            //             window.location.href = _redirect;
            //         }
            //     } else {
            //         d.msg && alert(d.msg);
            //     }
            // });
        });
    })();


})(this, jQuery, UTILS || {});(function( win, $, UTILS, undefined ){
    var $body = $('body');

    //[单选、复选、下拉框]默认选择
    ;(function(){
        /*
         * @params:
         *			dom: 操作对象
         * 			default: value or array (单选框、下拉框单个字符串值；复选框数组)
         */
        win.setDefault = function( obj ){
            var $dom = obj.dom || '';
            var _default = obj.default || ''; //type

            if( $dom.length > 0 ){
                var _type = $dom[0].type;
                var _length = $dom.length;

                switch( _type ){
                    case 'radio':
                        if( typeof(_default) != 'string' ) return;
                        for( var _i = 0; _i < _length; _i ++ ){
                            $dom.eq(_i).val() == _default && $dom.eq(_i).attr('checked', true);
                        }
                        break;

                    case 'checkbox':
                        if( Object.prototype.toString.call(_default).slice(8, -1) != 'Array' ) return;
                        for( var _i = 0; _i < _length; _i++ ){
                            for( var _a = 0, _len = _default.length; _a < _len; _a ++ ){
                                $dom.eq(_i).val() == _default[_a] && $dom.eq(_i).attr('checked', true);
                            }
                        }
                        break;

                    case 'select-one':
                        if( typeof(_default) != 'string' ) return;

                        var $option = $dom.find('option');
                        var _optLen = $option.length;

                        for( var _o = 0; _o < _optLen; _o++ ){
                            $option.eq(_o).val() == _default && $option.eq(_o).attr('selected', true);
                        }
                        break;
                }
            }
        }
    })();


    //添加配置项
    ;(function(){
        var controlRow = function( params ){
            var params = params || {};

            //容错
            if( !params.rowName || !params.addBtn || !params.delBtn || !params.item ){
                alert('错误提示: 缺少参数配置!');
                return;
            }

            var _parentName = '.' + params.rowName;
            var _tmpl = [];

            _tmpl.push('<div class="form-group '+ params.rowName +'"><label class="col-sm-2 control-label"></label>');

            var _i = 0;
            var _len = params.item.length;
            for( ; _i < _len; ++_i ){
                _tmpl.push('<div class="col-sm-1"><input type="text" name="'+ params.item[_i].name +'" placeholder="'+ params.item[_i].placeholder +'" value="" class="form-control"></div>');
            }
            _tmpl.push(		'<div class="col-sm-1 control-items">');
            _tmpl.push(			'<a href="javascript:;" title="添加" class="'+ params.addBtn +'">+</a>');
            _tmpl.push(			'<a href="javascript:;" title="删除" class="'+ params.delBtn +'">×</a>');
            _tmpl.push(		'</div>');
            _tmpl.push('</div>');

            //增加行
            $body.on('click', '.' + params.addBtn, function(e){
                var _this = $(this);
                var _parent = _this.closest( _parentName );

                _parent.after( _tmpl.join('') );
            });

            //删除行
            $body.on('click', '.' + params.delBtn, function(e){
                $(this).closest( _parentName ).remove();
            });
        };

        win.controlRow = controlRow;
    })();


    //ajax & form切换( post , btn )
    ;(function(){
        var
            $btn_get = $('.J_Do_Get'), //get形式按钮
            $btn_post = $('.J_Do_Post'); //post形式按钮

        //post btn
        $btn_post.off().on('click', function(e){
            var _this = $(this),
                _form = _this.closest('form');

            _form.off().submit(function(e){
                var _action = _form.attr('action');
                var _redirect = _form.attr('data-redirect');
                // var _item = _form.find('input');
                // var _params = [ '?' ];

                // for( var _i = 0, _len = _item.length; _i < _len; ++_i ){
                //     _params.push(
                //         _item.eq(_i).prop('name') + '=' + encodeURIComponent( _item.eq(_i).prop('value') ) + ( _i + 1 < _len ? '&' : '')
                //     );
                // }

                $.post( _action, _form.serialize(), function(d){
                    if( d && d.code == 0 ){
                        d.msg && alert( d.msg );
                        if( !!_redirect ){
                            window.location.href = _redirect;
                        }
                    } else {
                        d.msg && alert( d.msg );
                    }
                });
                // UTILS.load(_action + _params.join("") , {
                //     cb: 'cb'
                // }, function(d){
                //     if( d && d.code == 0 ){
                //         d.msg && alert( d.msg );
                //         if( !!_redirect ){
                //             window.location.href = _redirect;
                //         }
                //     } else {
                //         d.msg && alert( d.msg );
                //     }
                // });

                return false;
            });
        });

        //get btn
        $btn_get.off().on('click', function(e){
            var _this = $(this),
                _url = _this.attr('data-get-url'),
                _redirect = _this.attr('data-redirect');

            $.getJSON(_url, function(d){
                if( d && d.code == 0 ){
                    d.msg && alert( d.msg );
                    if( !!_redirect ){
                        window.location.href = _redirect;
                    }
                } else {
                    d.msg && alert(d.msg);
                }
            });
            // UTILS.load( _url, {
            //     cb: 'cb'
            // }, function(d){
            //     if( d && d.code == 0 ){
            //         d.msg && alert( d.msg );
            //         if( !!_redirect ){
            //             window.location.href = _redirect;
            //         }
            //     } else {
            //         d.msg && alert(d.msg);
            //     }
            // });
        });
    })();


})(this, jQuery, UTILS || {});