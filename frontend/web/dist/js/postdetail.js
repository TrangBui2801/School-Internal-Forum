$(document).ready(function () {
    // =================== Fade btn update ===================
    // $('.btnFade').fadeOut(0);
    // var fadeCkeditor = CKEDITOR.instances['editor1'];
    // fadeCkeditor.on('change', function () {
    //     $('.btnFade').fadeIn(200);
    // });

    // $('.fadeUpdate').on('keyup', function () {
    //     $('.btnFade').fadeIn(200);
    // });
    // =================== Fade btn cmt ===================
    $('.txtUpdatecmt').on('keyup', function () {
        $('.btn_cmtFade').fadeIn(200);
    });
    // =================== bnt Like ===================
    $(".btn-like").click(function () {
        $(this).toggleClass("liked");
        if ($(this).hasClass("liked"))
        {
            $('#like_count').html(parseInt($('#like_count').html()) + 1);
        }
        else
        {
            $('#like_count').html(parseInt($('#like_count').html()) - 1);
        }
    });
    // =================== bnt Comment ===================
    $(".btn-cmt").click(function () {
        $(this).toggleClass("cmted");
        $('#post-content').focus();
    });
    // =================== Comment ===================
    $(document).on('click', '.btn-reply', function (eve) {
        eve.preventDefault();
        $(this).parent().parent().siblings('.comment-footer').slideToggle();
        eve.stopImmediatePropagation();
    });
    //=================== Write your comment ===================
    $(document).on('click', '.write_cmt', function (eve) {
        eve.preventDefault();
        $('html, body').animate({ scrollTop: $('.post__footer-post-comment').offset().top - 100 }, 500);
    });
    // ajax function to like/unlike
    $(".btn-like").on("click", function() {
        let data = $(this).attr('data');
        let isLiked = $(this).hasClass('liked');
        $.ajax({
            type: "POST",
            url: "update-like",
            data: {
                id: data,
                isLiked: isLiked 
            },
            success: function(res) {
                if (res['status'] == 200)
                {
                    if (!isLiked)
                    {
                        $(this).toggleClass("liked");
                    }
                    $("#like_count_" + data).html(res['data']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            },
        });
    });
});