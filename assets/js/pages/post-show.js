const $ = require('jquery');

$(function() {
    const post_show = $('.post-show')
    if (post_show.length && post_show.data('is-auth') === 1) {
        const rateObj = $('.js-rate')
        const csrf = rateObj.data('csrf');
        let myRate = rateObj.data('rate');
        if (myRate === 1) {
            $('.js-rate-like').css('color', 'red')
        }
        if (myRate === -1) {
            $('.js-rate-dislike').css('color', 'red')
        }

        $(document).on('click', '.js-rate-dislike', () => {
            $.post( $('.js-rate-dislike').data('route'), {csrf} )
                .then(({rating}) => {
                    $('.js-rate-like').css('color', 'black')
                    $('.js-rate-dislike').css('color', 'red')
                    $('.js-rate-score').text(rating)
                })
        });

        $(document).on('click', '.js-rate-like', () => {
            $.post( $('.js-rate-like').data('route'), {csrf}  )
                .then(({rating}) => {
                    $('.js-rate-like').css('color', 'red')
                    $('.js-rate-dislike').css('color', 'black')
                    $('.js-rate-score').text(rating)
                })
        });
    }
});