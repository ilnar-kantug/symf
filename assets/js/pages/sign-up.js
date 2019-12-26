import $ from 'jquery'

$(function() {
    const sign_up = $('.sign-up')
    if (sign_up.length) {
        $(document).on('click', '.js-sign-up-terms', () => {
            $('#myModal').modal()
        });
    }
});