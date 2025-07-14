// jQuery(document).ready(function($) {
//     $('#publication-filter-form').on('submit', function(e) {
//         e.preventDefault();
//         var data = {
//             'action': 'filter_publications',
//             'category': $('#category').val(),
//             'tag': $('#tag').val(),
//             'year': $('#year').val()
//         };
//         $.post(ajax_object.ajax_url, data, function(response) {
//             $('#publication-results').html(response);
//         });
//     });
// });