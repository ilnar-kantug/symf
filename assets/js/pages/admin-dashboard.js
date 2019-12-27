import $ from 'jquery'

$(function() {
    const admin_dashboard = $('.admin-dashboard')
    if (admin_dashboard.length) {
        $(document).ready(function() {

            $('.datepicker').datepicker({
                format: "dd.mm.yyyy"
            });

            const elem = $('#select2-users-email');
            const autocomplete_url = elem.data('autocomplete-url')
            elem.select2({
                ajax : {
                    url : autocomplete_url,
                    type : 'POST',
                    dataType: "json",
                    delay: 250,
                    placeholder: "Type an email",
                    data: (params) => ({email: params.term}),
                    processResults: (data) => {
                        return {
                            results: data.map((user) => {
                                return {id: user.id, text: user.email}
                            })
                        }
                    }
                }
            });
        });
    }
});