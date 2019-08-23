<style>
    h2 {
        text-align: center;
        margin-bottom: 3rem;
    }

    .pile {
        height: 5rem;
        line-height: 5rem;
        text-align: center;
        font-size: 2.4rem;
        cursor: pointer;
    }

    .pile:hover {
        background: #e2e2e2;
    }
</style>
<div class="wrapper wrapper-content animated">
    <div class="ibox-content">
        <h2>在线电桩</h2>
        <div class="piles"></div>
    </div>
</div>
<script>
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        socket.send(JSON.stringify({do: 'pileList'}));
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            console.log(data);
            if (data.code === 500) {
                var str = '';
                $.each(data.data, function (k, v) {
                    str += '<div class="col-sm-3 pile" title="查看详情">';
                    str += v;
                    str += '</div>';
                });
                if (!str) {
                    str = '<div class="col-sm-12" style="text-align: center">没有充电桩在线</div>';
                }
                str += '<div style="clear: both"></div>';
                $('.piles').html(str);
                return;
            }
            window.showMsg(code[data.code]);
        };
    };
    $('.piles').on('click', '.pile', function () {
        window.location.href = '/pile/pile/info?no=' + $(this).text();
    })
</script>