import $ from 'jquery'

$(function() {
    const post_create = $('.post-create')
    if (post_create.length) {
        $(document).ready(function() {
            const elem = $('#select2-tags');
            const autocomplete_url = elem.data('autocomplete-url')
            elem.select2({
                ajax : {
                    url : autocomplete_url,
                    type : 'POST',
                    dataType: "json",
                    delay: 250,
                    placeholder: "Enter the tags",
                    data: (params) => ({tag: params.term}),
                    processResults: (data) => {
                        return {
                            results: data.map((tag) => {
                                return {id: tag.id, text: tag.name}
                            })
                        }
                    }
                }
            });
        });
    }
});