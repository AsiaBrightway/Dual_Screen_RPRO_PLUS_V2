const purchaseTable = new DataTable("#purchase_item_table", {
    scrollX: true,
    autoWidth: false,
});

$(".printBtn").printPage();

const infoContainer = document.querySelector(".purchase_details_info_container");
const listContainer = document.querySelector(".purchase_details_list_container");
const summaryContainer = document.querySelector(".purchase_details_summary_container");

function fixPurchaseDataTable() {
    setTimeout(() => {
        purchaseTable.columns.adjust();
    }, 300);
}

// Restore state
if (localStorage.getItem("showPurchaseInfo")) infoContainer.classList.add("show_container");
if (localStorage.getItem("showPurchaseSummary")) summaryContainer.classList.add("show_container");

if (localStorage.getItem("showPurchaseList")) {
    listContainer.classList.add("show_container");
    fixPurchaseDataTable();
}

function toggleSection(labelId, container, key) {
    const label = document.querySelector(labelId);

    label.addEventListener("click", () => {
        label.classList.toggle("show");
        container.classList.toggle("show_container");

        if (container.classList.contains("show_container")) {
            localStorage.setItem(key, "1");

            if (container === listContainer) {
                fixPurchaseDataTable();
            }
        } else {
            localStorage.removeItem(key);
        }
    });
}

toggleSection("#purchase_details_info_label", infoContainer, "showPurchaseInfo");
toggleSection("#purchase_details_list_label", listContainer, "showPurchaseList");
toggleSection("#purchase_details_summary_label", summaryContainer, "showPurchaseSummary");
