(function($) {

    $.numberFormat = function(target, options) {

        var defaults = {
                prefix: '',
                suffix: '',
                digits: 2,
                entryDigitSeparator: '.',
                finalDigitSeparator: '.',
                thousandSeparator: ',',
                rounding: true
            },
            settings, result,eNumber, nNumber, dNumber, fNumber,
            locale = {
                en: {
                    entryDigitSeparator: '.',
                    finalDigitSeparator: '.',
                    prefix: '$ '
                },
                fr: {
                    entryDigitSeparator: '.',
                    finalDigitSeparator: ',',
                    suffix: ' $'
                }
            };

        if (typeof options === "string") {
            settings = $.extend({}, true, defaults, locale[options]);
        } else {
            settings = $.extend({}, true, defaults, options);
        }

        target = parseFloat(target);

        eNumber = target.toString().split(settings.entryDigitSeparator);

        nNumber = eNumber[0];

        dNumber = !isNaN(eNumber[1]) ? eNumber[1] : "0";

        if (settings.rounding === true) {
            coef = 1;
            for(var i = 0; i < settings.digits; i++) {
                coef = coef * 10;
            }

            var newDigits = Math.round(parseFloat(target)*coef)/coef;

            newDigits = newDigits.toString();
            exploding = newDigits.split(settings.entryDigitSeparator);

            dNumber = !isNaN(exploding[1]) ? exploding[1] : "0";
        }

        while (dNumber.length > settings.digits) {
            dNumber = dNumber.substring(0, dNumber.length - 1);
        }

        while (dNumber.length < settings.digits) {
            dNumber += "0";
        }

        if (settings.digits < 1) {
            settings.digitSeparator = '';
        }

        fNumber = function(str) {
            str += '';
            var x = str.split('.'),
                x1 = x[0],
                x2 = x.length > 1 ? '.' + x[1] : '',
                rgx = /(\d+)(\d{3})/;

            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + settings.thousandSeparator + '$2');
            }
            return x1 + x2;
        }

        return settings.prefix + fNumber(nNumber + settings.finalDigitSeparator + dNumber) + settings.suffix;
    };

})(jQuery);
