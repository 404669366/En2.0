document.write("<script type='text/javascript' src='/js/dataTables/jquery.jeditable.js'></script>");
document.write("<script type='text/javascript' src='/js/dataTables/jquery.dataTables.js'></script>");
document.write("<script type='text/javascript' src='/js/dataTables/dataTables.bootstrap.js'></script>");
document.write("<style>" +
    ".tableSpan{float: left;margin:4px;}" +
    "</style>");
myTable = {};
myTable.params = {};

/**
 * 搜索
 * @returns {boolean}
 */
myTable.search = function () {
    $('body').on('click', '.tableSearch', function () {
        var data = {};
        $('.tableSearchBox').find('.searchField').each(function (k, v) {
            data[$(v).attr('name')] = $(v).val();
        });
        myTable.params = data;
        myTable.model.ajax.reload();
    });

    $('body').on('click', '.tableReload', function () {
        $('.tableSearchBox').find('.searchField').val('');
        myTable.params = {};
        myTable.model.ajax.reload();
    });
    return true;
};

/**
 * 导出
 * @returns {boolean}
 */
myTable.export = function () {
    $('body').on('click', '.export', function () {
        var url = $(this).attr('go') + '?';
        $('.tableSearchBox').find('.searchField').each(function (k, v) {
            url += $(v).attr('name') + '=' + $(v).val() + '&';
        });
        window.location.href = url
    });
    return true;
};

/**
 * 加载
 * @param config
 * @returns {boolean}
 */
myTable.load = function (config) {
    //验证是否已配置必要参数
    var need = ['table', 'url', 'columns', 'length', 'default_order'];
    var err = true;
    $.each(need, function (k, v) {
        if (eval('config.' + v) === undefined || eval('config.' + v) === 0 || eval('config.' + v) === {} || eval('config.' + v) === [] || eval('config.' + v) === '' || eval('config.' + v) === null) {
            console.log('必须配置参数:' + v);
            err = false;
        }
    });
    if (!err) {
        return false;
    }
    var lang = {
        "sProcessing": "处理中...",
        "sLengthMenu": "每页 _MENU_ 项",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项",
        "sInfoEmpty": "当前显示第 0 至 0 项，共 0 项",
        "sInfoFiltered": ""
    };
    myTable.model = $(config.table).DataTable({
        "iDisplayLength": config.length,//每页显示的条数
        "bLengthChange": false,//是否可以动态调整每页的显示条数
        "language": lang,//提示信息
        "serverSide": true,//启用服务器端分页
        "searching": false,//禁用原生搜索
        "columns": config.columns,
        "order": config.default_order,
        "ajax": function (data, callback, settings) {
            var params = {};
            params.order = '';
            params.sort = '';
            params.search = myTable.params;
            if (data.order.length) {
                params.order = config.columns[data.order[0].column].data;
                params.sort = data.order[0].dir;
            }
            params.start = data.start;
            params.length = data.length;
            $.getJSON(config.url, params, function (re) {
                var returnData = {};
                returnData.draw = data.draw;
                returnData.recordsTotal = re.total;
                returnData.recordsFiltered = re.total;
                returnData.data = re.data;
                callback(returnData);
            });
        }
    });
    return true;
};

/**
 * 基础加载
 * @param config
 * @returns {boolean}
 */
myTable.baseLoad = function (config) {
    //验证是否已配置必要参数
    var need = ['table', 'url', 'order'];
    var err = true;
    $.each(need, function (k, v) {
        if (eval('config.' + v) === undefined || eval('config.' + v) === 0 || eval('config.' + v) === {} || eval('config.' + v) === [] || eval('config.' + v) === '' || eval('config.' + v) === null) {
            console.log('必须配置参数:' + v);
            err = false;
        }
    });
    if (!err) {
        return false;
    }
    var lang = {
        "sProcessing": "处理中...",
        "sLengthMenu": "每页 _MENU_ 项",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项",
        "sInfoEmpty": "当前显示第 0 至 0 项，共 0 项",
        "sInfoFiltered": ""
    };
    myTable.model = $(config.table).DataTable({
        "language": lang,//提示信息
        "order": config.order,
        "ajax": config.url,
        "columns": config.columns
    });
    return true;
};