
/*
 * Notification : 
 * Owner : Sanket Virani
 */

var notify;
(function ($, window, document, undefined) {
    notify = {
        init: function (param) {
            return this;
        },
        desktop: {
            _title: '',
            _icon: 'star.ico',
            _body: '',
            init: function (param) {
                return this;
            },
            title: function ($string) {
                this._title = $string;
                return this;
            },
            icon: function (string) {
                this._icon = string;
                return this;
            },
            body: function (string) {
                this._body = string;
                return this;
            },
            /*Show Notification*/
            show: function () {
                if (Notification.permission !== 'granted') {
                    Notification.requestPermission();
                }
                n = new Notification(this._title, {
                    body: this._body,
                    /*icon : this._icon */
                });
            }
        },
        pnotify: {
            /*Show Notification*/
            _title: '',
            _titleHead: 'Alert',
            _body: '',
            _position: 'stack-topright',
            _type: 'success',
            _hide: true,
            _delay: 2000,
            _positionSort: "TR",
            init: function (param) {
                return this;
            },
            title: function ($string) {
                this._title = $string;
                return this;
            },
            titleHead: function (string) {
                this._titleHead = string;
                return this;
            },
            body: function (string) {
                this._body = string;
                return this;
            },
            delay: function (string) {
                this._delay = string;
                return this;
            },
            type: function (string) {
                this._type = string;
                return this;
            },
            position: function ($p) {
                switch ($p) {
                    case "TR" :
                    case "RT" :
                        this._position = 'stack-topright';
                        break;
                    case "TL" :
                    case "LT" :
                        this._position = 'stack-topleft';
                        break;
                    case "BR" :
                    case "RB" :
                        this._position = 'stack-bottomright';
                        break;
                    case "BL" :
                    case "LB" :
                        this._position = 'stack-bottomleft';
                        break;
                    default:
                        this._position = 'stack-topright';
                        break;
                }
                return this;
            },
            show: function () {
                new PNotify({
                    //   title: this._titleHead + ": " + this._title,
                    text: this._body,
                    type: this._type,
                    hide: true,
                    remove: true,
                    delay: this._delay,
                    styling: 'bootstrap3',
//                    addclass: this._position,
                });
            },
        }
    }
})(jQuery, window, document);

var call;
(function ($, window, document, d, p, undefined) {
    call = {
        test: function () {
            d.title('ttle').body('body').show();
            p.body('test').title('tllt').position('LT').type('danger').titleHead('Notice').show();
        }
    }
})(jQuery, window, document, notify.desktop, notify.pnotify);