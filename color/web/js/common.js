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

var MIXCOLOR = function (baseColor, moreColor, maxVal) {
    this.baseColor = JSON.stringify(baseColor || []);
    this.moreColor = moreColor || [];
    this.maxVal = maxVal || 100;
    this.mixColor = null;
    this.mix = function (ratios) {
        var now = JSON.parse(this.baseColor);
        if (ratios.length == this.moreColor.length) {
            for (var ri = 0, rl = ratios.length; ri < rl; ri++) {
                if (this.moreColor[ri].length == now.length) {
                    for (var ci = 0, ml = this.moreColor[ri].length; ci < ml; ci++) {
                        now[ci] += this.moreColor[ri][ci] * ratios[ri];
                        now[ci] = Math.min(now[ci], this.maxVal);
                    }
                }
            }
        }
        this.mixColor = now;
        return this;
    };
    this.mixToCMYK = function () {
        if (this.mixColor.length == 4) {
            return new CMYK(this.mixColor[0], this.mixColor[1], this.mixColor[2], this.mixColor[3]);
        }
        return false;
    };
    this.mixToRGB = function () {
        if (this.mixColor.length == 3) {
            return new RGB(this.mixColor[0], this.mixColor[1], this.mixColor[2]);
        }
        return false;
    }
};