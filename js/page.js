$('.pagination').on('click', ' li > a.ajaxify', function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    LoadPageContentBody(url);
});