$(document).ready(function() {

    /* ----------------------------------------------------------
     * DEFAULT TABLE INITIALIZATION (RUNS ONCE)
     * ---------------------------------------------------------- */
    const defaultTable = $('#stock_in_report_list_by_default').DataTable({
        scrollX: true,
        autoWidth: false,
        pageLength: 10,
        searching: false,
        ordering: true,
        info: true,
        lengthChange: true,
        language: { lengthMenu: "Show _MENU_ entries" },
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ]
    });

    /* ----------------------------------------------------------
     * SEARCH TABLE — DO NOT INITIALIZE NOW
     * (Initialize ONLY after search)
     * ---------------------------------------------------------- */
    let searchTable = null;


    /* ----------------------------------------------------------
     * SEARCH BUTTON CLICK
     * ---------------------------------------------------------- */
    $('#btn_top_sales_search').on('click', function(e) {
        e.preventDefault();

        const $button = $(this);
        const searchDate = $('#searchDate').val();
        const searchMonth = $('#searchMonth').val();
        const searchCategoryID = $('#categoryList').val();

        if (searchDate && searchMonth) {
            alert("Please select either a date or a month.");
            return;
        }

        $button.prop("disabled", true).html("Searching...");

        $.ajax({
            url: "render-top-sale-search",
            method: "GET",
            data: { searchDate, searchMonth, searchCategoryID },

            success: function(res) {

                $(".report_by_default_container").addClass("d-none");
                $(".report_by_search_container").removeClass("d-none");

                // Initialize search DataTable ONLY the first time
                if (!searchTable) {
                    searchTable = $('#stock_in_report_list_by_search').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        scrollX: true,
                        autoWidth: false
                    });
                }

                searchTable.clear();

                const cleanHtml = res.html.replace(/<\/?tbody[^>]*>/g, "").trim();

                if (!cleanHtml || res.totalOrders == 0) {
                    searchTable.row.add([
                        "", "", "", "No sales records found", "", "", "", ""
                    ]).draw(false);

                    $("#total_quantity").val("0");
                    $("#total_sales_amount").val("0.00");
                    $("#total_orders_count").val("0");
                    return;
                }

                $(cleanHtml).each(function() {
                    const tds = $(this).find("td");

                    if (tds.length !== 8) return;

                    const cells = tds.map((i, td) => $(td).text().trim()).get();

                    if (cells.every(v => v === "")) return;

                    searchTable.row.add(cells);
                });

                searchTable.draw(false);

                $("#total_quantity").val(Number(res.totalQuantity).toLocaleString());
                $("#total_sales_amount").val(
                    Number(res.totalSales).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );
                $("#total_orders_count").val(Number(res.totalOrders).toLocaleString());
            },

            complete: function() {
                $button.prop("disabled", false).html("Search");
            }
        });
    });



    /* ----------------------------------------------------------
     * RESET BUTTON
     * ---------------------------------------------------------- */
    $('#btn_top_sales_reset').on('click', function() {

        $('#searchDate').val('');
        $('#searchMonth').val('');
        $('#categoryList').val('0');

        $(".report_by_search_container").addClass("d-none");
        $(".report_by_default_container").removeClass("d-none");

        if (searchTable) {
            searchTable.clear().draw(false);
        }

        defaultTable.draw();
    });

    /* ----------------------------------------------------------
     * DATE / MONTH CLEARING LOGIC
     * ---------------------------------------------------------- */
    $('#searchDate').on('change', function() {
        if ($(this).val()) $('#searchMonth').val('');
    });

    $('#searchMonth').on('change', function() {
        if ($(this).val()) $('#searchDate').val('');
    });

});