/**
 * 获取数组中相同的元素个数
 * @param {*} search 
 */
Array.prototype.sameNum = function (search) {
    return this.filter(function (value) {
        return value == search;
    }).length;
};

/**
 * CMYK颜色封装类
 * @param {*} c 
 * @param {*} m 
 * @param {*} y 
 * @param {*} k 
 */
var CMYK = function (c, m, y, k) {
    this.c = c || 0;
    this.m = m || 0;
    this.y = y || 0;
    this.k = k || 0;
    this.toRGB = function () {
        return new RGB(255 * (1 - this.c / 100) * (1 - this.k / 100), 255 * (1 - this.m / 100) * (1 - this.k / 100), 255 * (1 - this.y / 100) * (1 - this.k / 100));
    };
};

var CMYKSET = function (cmyks) {
    this.cmyks = cmyks || [];
    this.add = function (cmyk) {
        this.cmyks.push(cmyk);
        return this;
    };
    this.del = function (key) {
        this.cmyks.splice(key, 1);
        return this;
    };
    this.num = function () {
        return this.cmyks.length;
    };
    this.mix = function (ratios) {
        if (ratios.length == this.cmyks.length) {
            var mix = [0,0,0,0];
            for (var ri = 0, rl = ratios.length; ri < rl; ri++) {
                for (var ci = 0, cl = this.cmyks[ri].length; ci < cl; ci++) {
                    mix[ci] += this.cmyks[ri][ci] * ratios[ri];
                    mix[ci] = Math.min(mix[ci], 100);
                }
            }
            console.log(mix);
            return new CMYK(mix[0], mix[1], mix[2], mix[3]);
        }
        return new CMYK();
    }
};

/**
 * RGB样色封装类
 * @param {*} r 
 * @param {*} g 
 * @param {*} b 
 */
var RGB = function (r, g, b) {
    this.r = r || 0;
    this.g = g || 0;
    this.b = b || 0;
    this.toCMYK = function () {
        var k = 1 - Math.max(this.r / 255, this.g / 255, this.b / 255);
        return new CMYK((1 - this.r / 255 - k) / (1 - k) * 100 || 0, (1 - this.g / 255 - k) / (1 - k) * 100 || 0, (1 - this.b / 255 - k) / (1 - k) * 100 || 0, k * 100);
    }
};