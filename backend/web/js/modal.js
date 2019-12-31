window.modal = function (config) {
    config = config || {};
    var node = config.node || 'modal';
    var nodeClass = '.' + node;
    var width = config.width || '40rem';
    var height = config.height || '20rem';
    var title = config.title || '';
    var content = config.content || '';
    var modalHtml =
        '<div class="' + node + '" style="display: table;position: fixed;left:0;top: 0;z-index: 999;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.4);">' +
        '    <span style="display: table-cell;vertical-align: middle">' +
        '        <div style="width:' + width + ';margin:0 auto;border-radius: 0.2rem;background: #FFFFFF">' +
        '            <div style="width: 100%;height: 6rem;line-height: 6rem;font-size: 2.4rem;text-align: center;border-bottom:1px solid silver;position: relative">' +
        '                <div class="close" style="width: 4rem;height: 4rem;line-height: 4rem;font-size:3rem;cursor: pointer;position: absolute;right: 0;top: 0;">' +
        '                    <i class="fa fa-times" aria-hidden="true"></i>' +
        '                </div>' + title +
        '            </div>' +
        '            <div class="content" style="width: 100%;height: ' + height + '">' + content + '</div>' +
        '            <div style="width: 100%;height: 6rem;border-top:1px solid silver;padding: 1rem 0;box-sizing: border-box;text-align: center">' +
        '                <button class="true" type="button" style="border: none;background: #23c6c8;color: #FFFFFF;height:4rem;line-height: 4rem;text-align: center;font-size: 2rem;box-sizing: border-box;padding: 0 3rem;border-radius: 0.2rem">确 认</button>' +
        '            </div>' +
        '        </div>' +
        '    </span>' +
        '</div>';
    $('body').css('overflow', 'hidden').append(modalHtml);
    $(nodeClass)
        .on('click', '.close', function () {
            $('body').css('overflow', 'auto');
            $(nodeClass).remove();
        })
        .on('click', '.true', function () {
            if (config.callback) {
                var even = $(nodeClass).find('.content');
                even.close = function () {
                    $('body').css('overflow', 'auto');
                    $(nodeClass).remove();
                };
                config.callback(even);
            }
        })
};

window.modal1 = function (config) {
    config = config || {};
    var node = config.node || 'modal';
    var nodeClass = '.' + node;
    $(nodeClass).remove();
    var width = config.width || '40rem';
    var height = config.height || '20rem';
    var title = config.title || '';
    var content = config.content || '';
    var modalHtml =
        '<div class="' + node + '" style="display: table;position: fixed;left:0;top: 0;z-index: 999;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.4);">' +
        '    <span style="display: table-cell;vertical-align: middle">' +
        '        <div style="width:' + width + ';margin:0 auto;border-radius: 0.2rem;background: #FFFFFF">' +
        '            <div style="width: 100%;height: 6rem;line-height: 6rem;font-size: 2.4rem;text-align: center;border-bottom:1px solid silver;position: relative">' +
        '                <div class="close" style="width: 4rem;height: 4rem;line-height: 4rem;font-size:3rem;cursor: pointer;position: absolute;right: 0;top: 0;">' +
        '                    <i class="fa fa-times" aria-hidden="true"></i>' +
        '                </div>' + title +
        '            </div>' +
        '            <div class="content" style="width: 100%;height: ' + height + '">' + content + '</div>' +
        '        </div>' +
        '    </span>' +
        '</div>';
    $('body').css('overflow', 'hidden').append(modalHtml);
    if (config.onLoad) {
        config.onLoad();
    }
    $(nodeClass)
        .on('click', '.close', function () {
            $('body').css('overflow', 'auto');
            $(nodeClass).remove();
        })
};