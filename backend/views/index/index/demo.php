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
    window.tree = function (element, name, lh, lw, bw, bh) {
        var ctx = document.getElementById(element).getContext('2d');
        var img = new Image();
        img.src = 'data:image/png;base64,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';
        var box = [];
        var du = {};
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
            var len = box.length;
            for (var i = 0; i < len; i++) {
                if (x >= box[i].x && x <= box[i].x + box[i].w && y >= box[i].y && y <= box[i].y + box[i].h) {
                    if (click) {
                        click(box[i]);
                    }
                }
            }
        }, false);
        return {
            draw: function (data) {
                box = [];
                var num = Object.keys(data).length;
                ctx.canvas.width = (num + lw) * bw;
                ctx.canvas.height = lh + bh + lw + bw / 3 + 40;
                ctx.lineWidth = lw;
                ctx.strokeStyle = '#2CBBEF';
                ctx.fillStyle = '#2CBBEF';
                ctx.textAlign = 'center';
                ctx.font = bw / 2.5 + 'px Arial';
                ctx.fillText(name, (ctx.canvas.width - ctx.lineWidth) / 2 + lw / 2, 30);
                if (num > 1) {
                    for (var key in data) {
                        var e = data[key];
                        ctx.moveTo((ctx.canvas.width - ctx.lineWidth) / 2 + lw / 2, 40);
                        var now = (ctx.canvas.width - lw - bw) / (num - 1) * (e.gun - 1) + (lw + bw) / 2;
                        ctx.lineTo(now, lh + 40);
                        ctx.strokeRect(now - bw / 2, lh + 40, bw, bh);
                        box.push({x: now - bw / 2, y: lh + 40, w: bw, h: bh, g: e.gun});
                        ctx.font = bw / 3 + 'px Arial';
                        ctx.fillText('DC' + e.gun, now, lh + bw / 2.5 + 40, bw - lw);
                        ctx.fillText(e.soc + '%', now, lh + bh + lw + bw / 3 + 40, bw - 5 * lw);
                        if (e.type === 0) {
                            ctx.fillText('空', now, lh + bh / 2 + 40, bw);
                            ctx.fillText('闲', now, lh + bh / 2 + bw / 2 + 40, bw);
                            if (du[e.gun]) {
                                clearInterval(du[e.gun].i);
                                ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e, bw - lw + 40, du[e.gun].e - lw);
                                delete du[e.gun];
                            }
                        }
                        if (e.type === 1) {
                            ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                            if (du[e.gun]) {
                                clearInterval(du[e.gun].i);
                                ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                                delete du[e.gun];
                            }
                        }
                        if (e.type === 2 && !du[e.gun]) {
                            ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                            du[e.gun] = {i: 0, e: 10};
                            du[e.gun].i = setInterval(function () {
                                ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                                du[e.gun].e += 10;
                                if (du[e.gun].e > bh) {
                                    du[e.gun].e = 10;
                                }
                                ctx.fillStyle = 'rgba(158,234,106,0.8)';
                                ctx.fillRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                                ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                                ctx.fillStyle = '#2CBBEF';
                                ctx.font = bw / 3 + 'px Arial';
                                ctx.fillText('DC' + e.gun, now, lh + bw / 2.5 + 40, bw - lw);
                            }, 500);
                        }
                    }
                } else {
                    var e = data[0];
                    ctx.moveTo((ctx.canvas.width - ctx.lineWidth) / 2 + lw / 2, 40);
                    var now = (ctx.canvas.width - ctx.lineWidth) / 2;
                    ctx.lineTo(now, lh + 40);
                    ctx.strokeRect(now - bw / 2, lh + 40, bw, bh);
                    box.push({x: now - bw / 2, y: lh + 40, w: bw, h: bh, g: data[0].gun});
                    ctx.font = bw / 3 + 'px Arial';
                    ctx.fillText('DC1', now, lh + bw / 2.5 + 40, bw - lw);
                    ctx.fillText(e.soc + '%', now, lh + bh + lw + bw / 3 + 40, bw - 5 * lw);
                    if (e.type === 0) {
                        ctx.fillText('空', now, lh + bh / 2 + 40, bw);
                        ctx.fillText('闲', now, lh + bh / 2 + bw / 2 + 40, bw);
                        if (du[e.gun]) {
                            clearInterval(du[e.gun].i);
                            ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                            delete du[e.gun];
                        }
                    }
                    if (e.type === 1) {
                        ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                        if (du[e.gun]) {
                            clearInterval(du[e.gun].i);
                            ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                            delete du[e.gun];
                        }
                    }
                    if (e.type === 2 && !du[e.gun]) {
                        ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                        du[e.gun] = {i: 0, e: 10};
                        du[e.gun].i = setInterval(function () {
                            ctx.clearRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                            du[e.gun].e += 10;
                            if (du[e.gun].e > bh) {
                                du[e.gun].e = 10;
                            }
                            ctx.fillStyle = 'rgba(158,234,106,0.8)';
                            ctx.fillRect(now - bw / 2 + lw / 2, lh + lw / 2 + bh - du[e.gun].e + 40, bw - lw, du[e.gun].e - lw);
                            ctx.drawImage(img, now - bw / 2 + 3 * lw, lh + bw / 2 + 40, bw - 6 * lw, bh - bw / 1.7);
                            ctx.fillStyle = '#2CBBEF';
                            ctx.font = bw / 3 + 'px Arial';
                            ctx.fillText('DC' + e.gun, now, lh + bw / 2.5 + 40, bw - lw);
                        }, 500);
                    }
                }
                ctx.stroke();
                return this;
            },
            onClick: function (callback) {
                click = callback;
                return this;
            }
        };
    };
    var tree = window.tree('tree', 2019093001, 80, 2, 60, 120).onClick(function (now) {
        console.log(now);
    });
    var socket = new WebSocket('ws://47.99.36.149:20001');
    socket.onopen = function () {
        socket.send(JSON.stringify({do: 'joinPile', pile: 2019093001}));
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            tree.draw(data.info);
        };
    };
</script>