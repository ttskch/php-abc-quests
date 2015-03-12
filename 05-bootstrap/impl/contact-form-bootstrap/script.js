$(function () {

    // フォーム送信時に確認ダイアログを出力.
    $('#contact-form').on('submit', function () {
        return confirm('送信しますか？');
    });

    // 「お問い合わせ種別」の切り替えを監視.
    $('#contact-form input[name="type"]').on('change', function () {
        $('#contact-form').find('.for-question, .for-comment').toggle();
    });

    // 「ご質問」用の要素を非表示に.
    $('.for-question').hide();

});
