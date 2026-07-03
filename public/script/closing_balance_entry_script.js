let closing_balance_entry_label = document.querySelector("#closing_balance_entry_label");
if (closing_balance_entry_label) {
    closing_balance_entry_label.addEventListener("click", (e) => {
        closing_balance_entry_label.classList.toggle("show");
        let closing_balance_entry_container = document.querySelector(".closing_balance_entry_container");
        if (closing_balance_entry_container) {
            closing_balance_entry_container.classList.toggle("show_container");
        }
    });
}

if (document.querySelector("#closing_balance_entry_table")) {
    new DataTable("#closing_balance_entry_table", {
        scrollX: true,
        scrollY: "520px",
        scrollCollapse: true,
        paging: false,
        searching: true,
        info: false,
        language: {
            emptyTable: "No records found"
        }
    });
}
