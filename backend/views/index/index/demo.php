<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
</style>
<canvas id="tree"></canvas>
<script>
    window.tree = function (element, name, count, lineSize, boxSize) {
        lineSize = lineSize || {width: 2, height: 80};
        boxSize = boxSize || {width: 60, height: 120};
        var car = new Image();
        car.src = '/img/car.png';
        var ctx = document.getElementById(element).getContext('2d');
        ctx.canvas.style.cursor = 'pointer';
        ctx.canvas.width = count * (2 * lineSize.width + boxSize.width + 10);
        ctx.canvas.height = boxSize.width + lineSize.height + boxSize.height;
        ctx.lineWidth = lineSize.width;
        ctx.fillStyle = '#2CBBEF';
        ctx.textAlign = 'center';
        ctx.font = boxSize.width * 0.45 + 'px Arial';
        ctx.fillText(name, ctx.canvas.width / 2, boxSize.width * 0.45, ctx.canvas.width);
        ctx.strokeStyle = '#2CBBEF';
        var unit = ctx.canvas.width / count;
        var box = {};
        while (count > 0) {
            ctx.moveTo(ctx.canvas.width / 2, boxSize.width * 0.5);
            ctx.lineTo((count - 0.5) * unit, boxSize.width * 0.5 + lineSize.height);
            ctx.strokeRect((count - 1) * unit + lineSize.width + 5, boxSize.width * 0.5 + lineSize.height, boxSize.width, boxSize.height);
            ctx.font = boxSize.width * 0.3 + 'px Arial';
            ctx.fillText('DC' + count, (count - 0.5) * unit, boxSize.width * 0.8 + lineSize.height, boxSize.width);
            ctx.font = boxSize.width * 0.3 + 'px Arial';
            ctx.fillText('正', (count - 0.5) * unit, boxSize.width * 1.3 + lineSize.height, boxSize.width);
            ctx.fillText('在', (count - 0.5) * unit, boxSize.width * 1.6 + lineSize.height, boxSize.width);
            ctx.fillText('连', (count - 0.5) * unit, boxSize.width * 1.9 + lineSize.height, boxSize.width);
            ctx.fillText('接', (count - 0.5) * unit, boxSize.width * 2.2 + lineSize.height, boxSize.width);
            ctx.fillText('???', (count - 0.5) * unit, boxSize.width * 0.85 + lineSize.height + boxSize.height, boxSize.width);
            box[count] = {
                boxSite: {
                    x: (count - 1) * unit + lineSize.width + 5,
                    y: boxSize.width * 0.5 + lineSize.height,
                    w: boxSize.width,
                    h: boxSize.height
                },
                gunSite: {
                    x: (count - 0.5) * unit,
                    y: boxSize.width * 0.8 + lineSize.height,
                    w: boxSize.width
                },
                fontSite: {
                    x: (count - 0.5) * unit,
                    y1: boxSize.width * 1.45 + lineSize.height,
                    y2: boxSize.width * 2.05 + lineSize.height,
                    w: boxSize.width
                },
                imgSite: {
                    x: (count - 1) * unit + lineSize.width + 5 + boxSize.width * 0.1,
                    y: boxSize.width * 0.9 + lineSize.height,
                    w: boxSize.width - boxSize.width * 0.2,
                    h: boxSize.height - boxSize.width * 0.5
                },
                socSite: {
                    x: (count - 0.5) * unit,
                    y1: boxSize.width * 0.85 + lineSize.height + boxSize.height,
                    y2: boxSize.width * 0.55 + lineSize.height + boxSize.height,
                    w: boxSize.width,
                    h: boxSize.height
                },
                socSize: {
                    index: 0,
                    now: 0,
                    max: boxSize.height,
                    x: (count - 1) * unit + lineSize.width + 5 + lineSize.width / 2,
                    y: boxSize.width * 0.5 + lineSize.height + lineSize.width / 2 + boxSize.height,
                    w: boxSize.width - lineSize.width,
                    h: -lineSize.width
                },
                gunInfo: {
                    gun: count,
                    link: false,
                    type: 0,
                    power: 0,
                    soc: 0,
                }
            };
            count--;
        }
        ctx.stroke();
        var click;
        ctx.canvas.addEventListener('click', function (e) {
            var x, y;
            if (e.layerX || e.layerX === 0) {
                x = e.layerX;
                y = e.layerY;
            }
            if (e.offsetX || e.offsetX === 0) {
                x = e.offsetX;
                y = e.offsetY;
            }
            for (var k in box) {
                if (x >= box[k].boxSite.x && x <= box[k].boxSite.x + box[k].boxSite.w && y >= box[k].boxSite.y && y <= box[k].boxSite.y + box[k].boxSite.h) {
                    if (click && box[k].gunInfo.link) {
                        click(box[k]);
                        return;
                    }
                }
            }
        }, false);
        return {
            draw: function (data) {
                clearInterval(box[data.gun].socSize.index);
                box[data.gun].gunInfo.link = true;
                box[data.gun].gunInfo.type = data.type;
                box[data.gun].gunInfo.power = data.power;
                box[data.gun].gunInfo.soc = data.soc;
                ctx.clearRect(box[data.gun].boxSite.x, box[data.gun].boxSite.y, box[data.gun].boxSite.w, box[data.gun].boxSite.h);
                ctx.strokeRect(box[data.gun].boxSite.x, box[data.gun].boxSite.y, box[data.gun].boxSite.w, box[data.gun].boxSite.h);
                ctx.fillStyle = '#2CBBEF';
                ctx.fillText('DC' + data.gun, box[data.gun].gunSite.x, box[data.gun].gunSite.y, box[data.gun].gunSite.w);
                ctx.clearRect(box[data.gun].boxSite.x, box[data.gun].socSite.y2, box[data.gun].socSite.w, box[data.gun].socSite.h);
                ctx.fillText(data.soc + '%', box[data.gun].socSite.x, box[data.gun].socSite.y1, box[data.gun].socSite.w);
                if (data.type === 0) {
                    ctx.fillText('空', box[data.gun].fontSite.x, box[data.gun].fontSite.y1, box[data.gun].fontSite.w);
                    ctx.fillText('闲', box[data.gun].fontSite.x, box[data.gun].fontSite.y2, box[data.gun].fontSite.w);
                    ctx.clearRect(box[data.gun].boxSite.x, box[data.gun].socSite.y2, box[data.gun].socSite.w, box[data.gun].socSite.h);
                    ctx.fillText('- - -', box[data.gun].socSite.x, box[data.gun].socSite.y1, box[data.gun].socSite.w);
                }
                if (data.type === 1) {
                    ctx.drawImage(car, box[data.gun].imgSite.x, box[data.gun].imgSite.y, box[data.gun].imgSite.w, box[data.gun].imgSite.h);
                }
                if (data.type === 2) {
                    ctx.drawImage(car, box[data.gun].imgSite.x, box[data.gun].imgSite.y, box[data.gun].imgSite.w, box[data.gun].imgSite.h);
                    box[data.gun].socSize.index = setInterval(function () {
                        ctx.clearRect(box[data.gun].boxSite.x, box[data.gun].boxSite.y, box[data.gun].boxSite.w, box[data.gun].boxSite.h);
                        ctx.strokeRect(box[data.gun].boxSite.x, box[data.gun].boxSite.y, box[data.gun].boxSite.w, box[data.gun].boxSite.h);
                        ctx.fillStyle = 'rgba(158,234,106,0.8)';
                        box[data.gun].socSize.now += 10;
                        if (box[data.gun].socSize.now > box[data.gun].socSize.max) {
                            box[data.gun].socSize.now = 10;
                        }
                        ctx.fillRect(box[data.gun].socSize.x, box[data.gun].socSize.y - box[data.gun].socSize.now, box[data.gun].socSize.w, box[data.gun].socSize.h + box[data.gun].socSize.now);
                        ctx.fillStyle = '#2CBBEF';
                        ctx.fillText('DC' + data.gun, box[data.gun].gunSite.x, box[data.gun].gunSite.y, box[data.gun].gunSite.w);
                        ctx.drawImage(car, box[data.gun].imgSite.x, box[data.gun].imgSite.y, box[data.gun].imgSite.w, box[data.gun].imgSite.h);
                    }, 500);
                }
                if (data.type === 3) {
                    ctx.fillStyle = '#D24D57';
                    ctx.fillText('故', box[data.gun].fontSite.x, box[data.gun].fontSite.y1, box[data.gun].fontSite.w);
                    ctx.fillText('障', box[data.gun].fontSite.x, box[data.gun].fontSite.y2, box[data.gun].fontSite.w);
                    ctx.fillStyle = '#2CBBEF';
                    ctx.clearRect(box[data.gun].boxSite.x, box[data.gun].socSite.y2, box[data.gun].socSite.w, box[data.gun].socSite.h);
                    ctx.fillText('- - -', box[data.gun].socSite.x, box[data.gun].socSite.y1, box[data.gun].socSite.w);
                }
                return this;
            },
            onClick: function (callback) {
                click = callback;
                return this;
            }
        };
    };
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        var tree = window.tree('tree', 2019093001, 10).onClick(function (now) {
            console.log(now);
        });
        socket.send(JSON.stringify({do: 'joinGuns', pile: 2019093001}));
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            tree.draw(data);
        };
    };
</script>