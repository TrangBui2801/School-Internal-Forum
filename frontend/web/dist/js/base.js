// ==============================change profile responsive====================================
var c = 0;
function change_profile() {
  if (c == 0) {
    document
      .getElementById("modal-body-profile-left")
      .classList.remove("showProfile");
    document
      .getElementById("modal-body-profile-right")
      .classList.remove("hideProfile");

    document
      .getElementById("modal-body-profile-left")
      .classList.add("hideProfile");
    document
      .getElementById("modal-body-profile-right")
      .classList.add("showProfile");
    c = 1;
  } else {
    document
      .getElementById("modal-body-profile-left")
      .classList.remove("hideProfile");
    document
      .getElementById("modal-body-profile-right")
      .classList.remove("showProfile");

    document
      .getElementById("modal-body-profile-left")
      .classList.add("showProfile");
    document
      .getElementById("modal-body-profile-right")
      .classList.add("hideProfile");
    c = 0;
  }
}

// ===========================================loading=========================================
$(window).on("load", function (event) {
  $("body").removeClass("preloading");
  $(".load").delay(700).fadeOut("fast");
});

// =================================Table rows clickable====================================
document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll("tr[data-href]");
  rows.forEach((row) => {
    row.addEventListener("click", () => {
      window.location.href = row.dataset.href;
    });
  });
});

// ====================================scroll hide header====================================

var prevScrollpos = window.pageYOffset;
window.onscroll = function () {
  var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.getElementById("header").style.top = "0";
  } else {
    document.getElementById("header").style.top = "-40px";
  }
  prevScrollpos = currentScrollPos;
};

// ====================================auto resize textarea====================================
$("textarea")
  .each(function () {
    this.setAttribute(
      "style",
      "height:" + this.scrollHeight + "px;overflow-y:hidden;"
    );
  })
  .on("input", function () {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
  });

// ====================================Limit date for input[type = date]====================================
var today = new Date();
var currentYear = today.getFullYear();
// nếu ngày nhỏ hơn 10 thì thêm 0 đằng trước
if (today.getDate() < 10) {
  var currentDate = "0" + today.getDate();
} else {
  var currentDate = today.getDate();
}
// nếu tháng nhỏ hơn 10 thì thêm 0 đằng trước
if (today.getMonth() < 10) {
  var currentMonth = "0" + (today.getMonth() + 1);
} else {
  var currentMonth = today.getMonth() + 1;
}
var minDate = currentYear - 55 + "-" + currentMonth + "-" + currentDate;
var maxDate = currentYear - 18 + "-" + currentMonth + "-" + currentDate;
// thêm thuộc tính giới hạn date cho input type = date
$("input[type=date]").attr("min", minDate);
$("input[type=date]").attr("max", maxDate);
// ====================================scrollTop====================================
$("#scrollTop").click(function (e) {
  e.preventDefault();
  $("html,body").animate(
    {
      scrollTop: 0,
    },
    1500,
    "easeInOutExpo"
  );
});
// ========================================================================
$(".header_bottom-main-nav li a").click(function () {
  $(".header_bottom-main-nav li a").removeClass("active");
  $(this).addClass("active");
});

$(document).ready(function () {
  $("#not-remind-btn").click(function () {
    if ($(this).is(":checked")) {
      let surveyId = $(this).attr("data");
      $.ajax({
        type: "POST",
        url: "survey/not-remind-survey",
        data: {
          surveyId: surveyId,
        },
        success: function (res) {
          if (res["status"] == 200) {
            clearInterval($myTimer);
            $(".popup-wrap").fadeOut(500);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert("Error: " + errorThrown);
        },
      });
      clearInterval($myTimer);
      $(".popup-wrap").fadeOut(500);
    }
  });
  $duration = 10;
  $(".seconds").text($duration);
  $(".popup-wrap").fadeIn(1500);

  $myTimer = setInterval(function () {
    startTimer();
  }, 1000);
  $(".popup .btn-close").on("click", function () {
    clearInterval($myTimer);
    $(".popup-wrap").fadeOut(500);
  });

  function startTimer() {
    $duration--;
    $(".seconds").text($duration);
    if ($duration == 0) {
      clearInterval($myTimer);
      $(".popup-wrap").fadeOut(500);
    }
  }
});

$(document).ready(function() {
  $('.show-notification').on('click', function(event) {
    console.log("Check"); 
  })
})


