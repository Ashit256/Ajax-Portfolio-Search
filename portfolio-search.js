jQuery(document).ready(function($) {
    $('#portfolio-search').on('keyup', function() {
        var searchQuery = $(this).val();

         if (searchQuery === '') {
            $('#portfolio-results').empty(); // Clear results if search field is empty
            return;
        }

        $.ajax({
            url: portfolioAjax.ajaxurl1,
            type: 'post',
            data: {
                action: 'portfolio_ajax_search',
                searchQuery: searchQuery
            },
            success: function(response) {
                $('#portfolio-results').html(response);
            }
        });
    });
});
