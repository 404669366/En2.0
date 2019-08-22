window.modal = function () {
    var toDo = {
        open: function () {
            document.body.style.overflowY = 'hidden';
        },
        close: function () {
            document.body.style.overflowY = 'visible';
        }
    };

    return {
        open: function (modalBox) {
            $(modalBox).show();
            toDo.open();
        },
        close: function (modalBox) {
            $(modalBox).hide();
            toDo.close();
        }
    };
}();
