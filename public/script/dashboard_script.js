/********Product doughnut chart*********** */
const expValue = "1000";
const product_ctx = document
    .getElementById("productDoughnutChart")
    .getContext("2d");
const productChart = new Chart(product_ctx, {
    type: "doughnut",
    data: {
        labels: ["Total Products", "Expected Products"],
        datasets: [
            {
                label: "# of votes",
                data: ["1000", expValue - "1000"],
                backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                hoverBorderColor: [
                    "rgba(255, 99, 132,0.2)",
                    "rgba(170, 170, 170,0.2)",
                ],
                borderWidth: 1,
            },
        ],
    },
    options: {
        aspectRatio: 1.2,
        plugins: {
            legend: {
                position: "bottom",
                align: "start",
            },
        },
        scales: {
            x: {
                display: false,
            },
            y: {
                beginAtZero: true,
                display: false,
            },
        },
        // cutoutPercentage: 80
    },
});
/********Category doughnut Chart*********** */
const category_ctx = document
    .getElementById("categoryDoughnutChart")
    .getContext("2d");
const categoryChart = new Chart(category_ctx, {
    type: "doughnut",
    data: {
        labels: ["Total Categories", "Expected Categories"],
        datasets: [
            {
                label: "# of votes",
                data: ["89", "100"],
                backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                hoverBorderColor: [
                    "rgba(255, 99, 132,0.2)",
                    "rgba(170, 170, 170,0.2)",
                ],
                borderWidth: 1,
            },
        ],
    },
    options: {
        aspectRatio: 1.2,
        plugins: {
            legend: {
                position: "bottom",
                align: "start",
            },
        },
        scales: {
            x: {
                display: false,
            },
            y: {
                beginAtZero: true,
                display: false,
            },
        },
        // cutoutPercentage: 80
    },
});

/********Customer doughnut Chart*********** */
const customer_ctx = document
    .getElementById("customerDoughnutChart")
    .getContext("2d");
const customerChart = new Chart(customer_ctx, {
    type: "doughnut",
    data: {
        labels: ["Total Customers", "Expected Customers"],
        datasets: [
            {
                label: "# of votes",
                data: ["189", "500"],
                backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                hoverBorderColor: [
                    "rgba(255, 99, 132,0.2)",
                    "rgba(170, 170, 170,0.2)",
                ],
                borderWidth: 1,
            },
        ],
    },
    options: {
        aspectRatio: 1.2,
        plugins: {
            legend: {
                position: "bottom",
                align: "start",
            },
        },
        scales: {
            x: {
                display: false,
            },
            y: {
                beginAtZero: true,
                display: false,
            },
        },
        // cutoutPercentage: 80
    },
});

/********Sales Quantity Bar Chart*********** */
var salesQty_ctx = document.getElementById("salesQtyBarChart").getContext("2d");

var salesQty_myChart = new Chart(salesQty_ctx, {
    type: "bar",
    data: {
        labels: ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"],
        datasets: [
            {
                label: "Oppa",
                data: [100, 80, 100, 50, 100, 350, 300],
                backgroundColor: "#6f44d1",
                stack: "Stack 0",
            },
            {
                label: "SPY",
                data: [120, 100, 80, 100, 110, 220, 350],
                backgroundColor: "#e86813",
                stack: "Stack 0",
            },
            {
                label: "Tiger",
                data: [200, 180, 150, 200, 160, 320, 350],
                backgroundColor: "#785374",
                stack: "Stack 1",
            },
            {
                label: "Full Moon",
                data: [90, 80, 88, 80, 90, 120, 160],
                backgroundColor: "#c9b853",
                stack: "Stack 1",
            },
        ],
    },
    options: {
        aspectRatio: 1.7,
        plugins: {
            legend: {
                position: "bottom",
                align: "start",
            },
        },
        responsive: true,
        interaction: {
            intersect: true,
        },
        scales: {
            x: {
                grid: {
                    display: false,
                    drawBorder: false,
                },
            },
            y: {
                grid: {
                    display: false,
                    drawBorder: false,
                },
                stacked: false,
            },
        },
    },
});

/********Sales_N_Purchase line Chart*********** */

// setup
const data = {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [
        {
            label: "Sales",
            data: [200000, 300000, 150000, 100000, 250000, 200000, 250000],
            backgroundColor: ["#512DA8"],
            borderColor: ["#6f44d1"],
            tension: 0.4,
        },
        {
            label: "Purchases",
            data: [300000, 100000, 200000, 150000, 100000, 200000, 150000],
            backgroundColor: ["rgb(255, 26, 104,1)"],
            borderColor: ["rgb(255, 26, 104,1)"],
            tension: 0.4,
        },
    ],
};

//config
const config = {
    type: "line",
    data: data,
    options: {
        aspectRatio: 1.7,
        plugins: {
            legend: {
                position: "bottom",
                align: "start",
            },
        },
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    },
};

// render init block
const myChart = new Chart(
    document.getElementById("salesNpurchaseLineChart"),
    config
);
