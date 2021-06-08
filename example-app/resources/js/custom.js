"use strict";

var isSubmit = false;

$(function() {
    $("#btn-submit").click(() => {
        var code = $("#code").val();
        if (!code) {
            alert("Vui lòng nhập mã dự thưởng.");
            return;
        }

        if (isSubmit) {
            return;
        }
        isSubmit = true;

        axios
            .post(`/gift/spin-gift`, {
                code
            })
            .then(function(response) {
                if (response.status == 200) {
                    var data = response.data.data;
                    if (data) {
                        $(".gift-img").attr("src", "/" + data.img_src);
                        $(".gift-name").text(data.name);
                        $("#modalGift").modal("show");
                    } else {
                        var message =
                            response.data.message ?? "Lỗi, vui lòng thử lại!";
                        alert(message);
                    }
                }
                isSubmit = false;
            })
            .catch(function(error) {
                isSubmit = false;
                console.log("error", error);
                alert("Lỗi, vui lòng thử lại!");
            });
    });

    //redeem point
    $(".btn-redeem-point").click(function(){
        var userId = $(this).data("user-id");
        if (!userId) {
            alert("Lỗi, vui lòng thử lại!");
            return;
        }

        if (isSubmit) {
            return;
        }
        isSubmit = true;

        axios
            .post(`/gift/redeem-point`, {
                userId: userId,
            })
            .then(function(response) {
                if (response.status == 200) {
                    var status = response.data.status;
                    if (status) {
                        alert('Xin chúc mừng, bạn đã đổi điểm thành công!')
                        setTimeout(() => {
                            location.reload();
                        }, 1000)
                    } else {
                        var message =
                            response.data.message ?? "Lỗi, vui lòng thử lại!";
                        alert(message);
                    }
                }
                isSubmit = false;
            })
            .catch(function(error) {
                isSubmit = false;
                console.log("error", error);
                alert("Lỗi, vui lòng thử lại!");
            });
    });
});
