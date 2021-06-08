/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/


var isSubmit = false;
$(function () {
  $("#btn-submit").click(function () {
    var code = $("#code").val();

    if (!code) {
      alert("Vui lòng nhập mã dự thưởng.");
      return;
    }

    if (isSubmit) {
      return;
    }

    isSubmit = true;
    axios.post("/gift/spin-gift", {
      code: code
    }).then(function (response) {
      if (response.status == 200) {
        var data = response.data.data;

        if (data) {
          $(".gift-img").attr("src", "/" + data.img_src);
          $(".gift-name").text(data.name);
          $("#modalGift").modal("show");
        } else {
          var _response$data$messag;

          var message = (_response$data$messag = response.data.message) !== null && _response$data$messag !== void 0 ? _response$data$messag : "Lỗi, vui lòng thử lại!";
          alert(message);
        }
      }

      isSubmit = false;
    })["catch"](function (error) {
      isSubmit = false;
      console.log("error", error);
      alert("Lỗi, vui lòng thử lại!");
    });
  }); //redeem point

  $(".btn-redeem-point").click(function () {
    var userId = $(this).data("user-id");

    if (!userId) {
      alert("Lỗi, vui lòng thử lại!");
      return;
    }

    if (isSubmit) {
      return;
    }

    isSubmit = true;
    axios.post("/gift/redeem-point", {
      userId: userId
    }).then(function (response) {
      if (response.status == 200) {
        var status = response.data.status;

        if (status) {
          alert('Bạn đã đổi điểm thành công!');
          setTimeout(function () {
            location.reload();
          }, 2000);
        } else {
          var _response$data$messag2;

          var message = (_response$data$messag2 = response.data.message) !== null && _response$data$messag2 !== void 0 ? _response$data$messag2 : "Lỗi, vui lòng thử lại!";
          alert(message);
        }
      }

      isSubmit = false;
    })["catch"](function (error) {
      isSubmit = false;
      console.log("error", error);
      alert("Lỗi, vui lòng thử lại!");
    });
  });
});
/******/ })()
;