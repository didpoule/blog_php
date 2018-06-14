function Pagination(action, max_per_page, name, nb) {

    this.name = name;
    this.action = action;
    this.nb = nb;
    this.current_page_container = $("#current-page");
    this.current_page = 0;
    this.prevBtn = $("#prev");
    this.nextBtn = $("#next");
    this.navigation = $("#pagination-nav");
    this.max_per_page = max_per_page;
    this.nb_pages = Math.ceil(this.nb / this.max_per_page);
    this.offset = 0;

    if (this.nb_pages > 1) {
        this.navigation.show().css("display", "flex");
    }

    this.next = function (action, offset, max_per_page) {
        if (this.current_page + 1 > this.nb_pages) {
            return null;
        }

        this.offset += max_per_page;

        $.ajax({
            dataType: "json",
            url: this.action + this.offset + "/" + this.max_per_page,
            timeout: 4000,

            success: function (data) {

                var items = [];
                $.each(data, function (key, val) {
                    items.push("<div>" +
                        "<h3>" + val.title + "</h3>" +
                        "<span>" + val.date + "</span>" +
                        "<p>" + val.content + "</p>" +
                        "</div>");
                });
                $("#paginated-content").html(items);
            },

        });
        this.current_page++;
        this.current_page_container.html(this.current_page);

    };

    this.prev = function (action, offset, max_per_page) {
        if (this.current_page - 1 < 0) {
            return null;
        }

        this.offset -= max_per_page;

        $.ajax({
            dataType: "json",
            url: this.action + this.offset + "/" + this.max_per_page,
            timeout: 4000,

            success: function (data) {

                var items = [];
                $.each(data, function (key, val) {
                    items.push("<div>" +
                        "<h3>" + val.title + "</h3>" +
                        "<span>" + val.date + "</span>" +
                        "<p>" + val.content + "</p>" +
                        "</div>");
                });
                $("#paginated-content").html(items);
            },
        });
        this.current_page--;
        this.current_page_container.html(this.current_page);
    };
    $this = this;

    this.nextBtn.click(function () {
        $this.next($this.action, $this.offset, $this.max_per_page)
    });

    this.prevBtn.click(function () {
        $this.prev($this.action, $this.offset, $this.max_per_page)

    });

}


