// public/js/autocomplete-phong.js
window.initRoomAutocomplete = function (inputSelector, options = {}) {
    const $input = $(inputSelector);
    $input.autocomplete({
        source: options.source || function (request, response) {
            $.ajax({
                url: options.url,
                method: "GET",
                data: {
                    q: request.term
                },
                success: function (data) {
                    if (data) {
                        response(data.map(function (item) {
                            return {
                                label: item.maphong + " (" + item.tenphong + ")",
                                id: item.id,
                                mays: item.mays,
                                tenphong: item.tenphong,
                                tengvql: item.tengvql,
                            };
                        }));
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        },
        appendTo: $input.closest(".autocomplete"),
        select: options.select,
        change: options.change,
    });

    const updateAutocompleteWidth = () => {
        const $menu = $input.autocomplete("widget");
        const inputWidth = $input.outerWidth();
        $menu.css("width", inputWidth + "px");
    };

    $input.on("autocompleteopen", updateAutocompleteWidth);
    $(window).on("resize", updateAutocompleteWidth);
}
// function initPhongAutocomplete(inputSelector, options = {}) {
//     const $input = $(inputSelector);
//     $input.autocomplete({
//         source: options.source || function(request, response) {
//             $.ajax({
//                 url: "{{ route('nhatkyphongmay.search-phong') }}",
//                 method: "GET",
//                 data: {
//                     q: request.term
//                 },
//                 success: function(data) {
//                     if (data) {
//                         response(data.map(function(item) {
//                             return {
//                                 label: item.maphong + " (" + item.tenphong + ")",
//                                 id: item.id,
//                                 mays: item.mays,
//                                 tenphong: item.tenphong,
//                                 tengvql: item.tengvql,
//                             };
//                         }));
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     console.error("Error fetching data:", error);
//                 }
//             });
//         },
//         appendTo: options.appendTo || ".form-group.autocomplete",
//         select: options.select,
//         change: options.change,
//     });
//     const updateAutocompleteWidth = () => {
//         const $menu = $input.autocomplete("widget");
//         const inputWidth = $input.outerWidth();
//         $menu.css("width", inputWidth + "px");
//     };
//     $input.on("autocompleteopen", updateAutocompleteWidth);
//     $(window).on("resize", updateAutocompleteWidth);
// }
