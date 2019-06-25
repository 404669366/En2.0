function myTree(config) {
    config.data = JSON.parse(config.data);
    if (config.default) {
        config.default = JSON.parse(config.default);
        var arr = [];
        for (var i in config.default) {
            arr.push(config.default[i]); //属性
        }
        config.default = arr;
    }
    var checkbox = ' type="radio" name="' + config.name + '" ';
    if (config.checkbox) {
        checkbox = ' type="checkbox" name="' + config.name + '[]" ';
        $(config.element).on('click', '.father', function () {
            if ($(this).prop('checked') === false) {
                $(this).parent('li').next().find('.son').prop('checked', false);
            }else {
                $(this).parent('li').next().find('.son').prop('checked', true);
            }
        });

        $(config.element).on('click', '.son', function () {
            if ($(this).prop('checked') === true) {
                $(this).parent('li').parent('ul').prev().find('.father').prop('checked', true);
            } else {
                var ndaklwnd = false;
                $(this).parent('li').parent('ul').find('.son').each(function (k, v) {
                    if ($(v).prop('checked') === true) {
                        ndaklwnd = true;
                    }
                });
                $(this).parent('li').parent('ul').prev().find('.father').prop('checked', ndaklwnd);
            }
        });
    }
    var tree = '';
    $.each(config.data, function (k, v) {
        tree += '<li><i class="fa fa-plus"></i>&emsp;' + v.key + '&nbsp;<input ';
        if (config.default && isInArray(config.default, v.value)) {
            tree += 'checked';
        }
        tree += checkbox + 'value="' + v.value + '" class="father"></li>';
        if (v.son) {
            tree += '<ul class="myTree treeSon">';
            $.each(v.son, function (sk, sv) {
                tree += '<li>' + sv.key + '&nbsp;<input ';
                if (config.default && isInArray(config.default, sv.value)) {
                    tree += 'checked';
                }
                tree += checkbox + 'value="' + sv.value + '" class="son"></li>';
            });
            tree += '</ul>';
        }
    });
    $(config.element).append(tree);
    $('.treeSon').hide();
    $(config.element).css('list-style-type', 'none').find('li').css('height', '2rem').css('line-height', '2rem');

    $(config.element).on('click', '.fa', function () {
        if ($(this).attr('class') === 'fa fa-plus') {
            $(this).removeClass('fa-plus').addClass('fa-minus');
            $(this).parent('li').next().fadeIn();
        } else {
            $(this).removeClass('fa-minus').addClass('fa-plus');
            $(this).parent('li').next().fadeOut();
        }
    });

    function isInArray(arr, val) {
        var str = "," + arr.join(",") + ",";
        if (str.indexOf("," + val + ",") != "-1") {
            return true;
        }
        return false;
    }
}