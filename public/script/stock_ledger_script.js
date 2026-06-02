let stock_ledger_list_label = document.querySelector("#stock_ledger_list_label");
if (stock_ledger_list_label) {
    stock_ledger_list_label.addEventListener("click", (e) => {
        stock_ledger_list_label.classList.toggle("show");
        let stock_ledger_list_container = document.querySelector(".stock_ledger_list_container");
        if (stock_ledger_list_container) {
            stock_ledger_list_container.classList.toggle("show_container");
        }
    });
}

if (document.querySelector("#stock_ledger_list")) {
    new DataTable("#stock_ledger_list", {
        scrollX: true,
        scrollY: "500px",
        scrollCollapse: true,
        pageLength: 10,
        language: {
            emptyTable: "No records found"
        }
    });
}
