@extends('layouts.admin.master')
@section('title', 'Dashboard')

@section('content')

    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Dashboard</span>
        </div>
        <div class="home-content">
            <section class="content-section" style="padding-top:20px;margin-left:10px">
                <div class="row pie_chart_row">
                    <div class="col-md-4 col-sm-12">
                        <div class="chart">
                            <div class="chart_title">
                                <label>Products</label>
                            </div>
                            <div class="chart_content">
                                <canvas id="productDoughnutChart" class="doughnut-style"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="chart">
                            <div class="chart_title">
                                <label>Sale Quantities</label>
                            </div>
                            <div class="chart_content">
                                <canvas id="saleQtyDoughnutChart" class="doughnut-style"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="chart">
                            <div class="chart_title">
                                <label>Total Sales</label>
                            </div>
                            <div class="chart_content">
                                <canvas id="salesDoughnutChart" class="doughnut-style"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row line_chart_row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="chart">
                            <div class="chart_title">
                                <label>Top Sale Items</label>
                            </div>
                            <canvas id="salesQtyBarChart" class="chart-style"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="chart">
                            <div class="chart_title">
                                <label>Sales And Purchases</label>
                            </div>
                            <canvas id="salesNpurchaseLineChart" class="chart-style"></canvas>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </section>
    <script src="{{ asset('script/links_js/chart.3.8.0.min.js') }}"></script>
    <script>
        function getTop3(items) {
            return [
                Number(items[0]?.total_quantity ?? 0),
                Number(items[1]?.total_quantity ?? 0),
                Number(items[2]?.total_quantity ?? 0),
            ];
        }


        var menuItems = @json($menuItems);
        var saleQuantitites = @json($saleQuantitites);
        var expectedSaleQty = Number(saleQuantitites) + 100;
        var sales = @json($sales);

        var saleWeek = @json($saleWeek);
        var purchaseWeek = @json($purchaseWeek);
        var saleItemWeek = @json($saleItemWeek);
        var saleItemWeekList = @json($saleItemWeekList);
        console.log(saleItemWeekList);

        /********Product doughnut chart*********** */
        const expValue = menuItems.length;
        const product_ctx = document
            .getElementById("productDoughnutChart")
            .getContext("2d");
        const productChart = new Chart(product_ctx, {
            type: "doughnut",
            data: {
                labels: ["Total Products", "Expected Products"],
                datasets: [{
                    label: "# of votes",
                    data: [menuItems.length, expValue - menuItems.length],
                    backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                    hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                    hoverBorderColor: [
                        "rgba(255, 99, 132,0.2)",
                        "rgba(170, 170, 170,0.2)",
                    ],
                    borderWidth: 1,
                }, ],
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
        /********Sale Quantities doughnut Chart*********** */
        const saleQuantities_ctx = document
            .getElementById("saleQtyDoughnutChart")
            .getContext("2d");
        const saleQuantitiesChart = new Chart(saleQuantities_ctx, {
            type: "doughnut",
            data: {
                labels: ["Total Sale Quantities", "Expected Sale Quantities"],
                datasets: [{
                    label: "# of votes",
                    data: [saleQuantitites, expectedSaleQty],
                    backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                    hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                    hoverBorderColor: [
                        "rgba(255, 99, 132,0.2)",
                        "rgba(170, 170, 170,0.2)",
                    ],
                    borderWidth: 1,
                }, ],
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
            .getElementById("salesDoughnutChart")
            .getContext("2d");
        const customerChart = new Chart(customer_ctx, {
            type: "doughnut",
            data: {
                labels: ["Total Sales", "Expected Sales"],
                datasets: [{
                    label: "# of votes",
                    data: [sales.length, "0"],
                    backgroundColor: ["#6f44d1", "rgba(170, 170, 170,1)"],
                    hoverBackgroundColor: ["#512DA8", "rgba(170, 170, 170,0.2)"],
                    hoverBorderColor: [
                        "rgba(255, 99, 132,0.2)",
                        "rgba(170, 170, 170,0.2)",
                    ],
                    borderWidth: 1,
                }, ],
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


        var saleItemForMonday = saleItemWeek['saleItemForMonday'];
        var saleItemForTuesday = saleItemWeek['saleItemForTuesday'];
        var saleItemForWednesday = saleItemWeek['saleItemForWednesday'];
        var saleItemForTursday = saleItemWeek['saleItemForTursday'];
        var saleItemForFriday = saleItemWeek['saleItemForFriday'];
        var saleItemForSaturday = saleItemWeek['saleItemForSaturday'];
        var saleItemForSunday = saleItemWeek['saleItemForSunday'];

        var topOneItemForMonday = 0;
        var topTwoItemForMonday = 0;
        var topThreeItemForMonday = 0;

        var topOneItemForTuesday = 0;
        var topTwoItemForTuesday = 0;
        var topThreeItemForTuesday = 0;

        var topOneItemForWednesday = 0;
        var topTwoItemForWednesday = 0;
        var topThreeItemForWednesday = 0;

        var topOneItemForTursday = 0;
        var topTwoItemForTursday = 0;
        var topThreeItemForTursday = 0;

        var topOneItemForFriday = 0;
        var topTwoItemForFriday = 0;
        var topThreeItemForFriday = 0;

        var topOneItemForSaturday = 0;
        var topTwoItemForSaturday = 0;
        var topThreeItemForSaturday = 0;

        var topOneItemForSunday = 0;
        var topTwoItemForSunday = 0;
        var topThreeItemForSunday = 0;

        // =======================
        // ASSIGN TOP 3 PER DAY ✅
        // =======================
        [
            topOneItemForMonday,
            topTwoItemForMonday,
            topThreeItemForMonday
        ] = getTop3(saleItemForMonday);

        [
            topOneItemForTuesday,
            topTwoItemForTuesday,
            topThreeItemForTuesday
        ] = getTop3(saleItemForTuesday);

        [
            topOneItemForWednesday,
            topTwoItemForWednesday,
            topThreeItemForWednesday
        ] = getTop3(saleItemForWednesday);

        [
            topOneItemForTursday,
            topTwoItemForTursday,
            topThreeItemForTursday
        ] = getTop3(saleItemForTursday);

        [
            topOneItemForFriday,
            topTwoItemForFriday,
            topThreeItemForFriday
        ] = getTop3(saleItemForFriday);

        [
            topOneItemForSaturday,
            topTwoItemForSaturday,
            topThreeItemForSaturday
        ] = getTop3(saleItemForSaturday);

        [
            topOneItemForSunday,
            topTwoItemForSunday,
            topThreeItemForSunday
        ] = getTop3(saleItemForSunday);

        console.log(
            'Wednesday qty:',
            topOneItemForWednesday,
            topTwoItemForWednesday,
            topThreeItemForWednesday
        );

        var salesQty_ctx = document.getElementById("salesQtyBarChart").getContext("2d");
        saleItemWeekList = saleItemWeekList ?? [];

        while (saleItemWeekList.length < 3) {
            saleItemWeekList.push({
                item_name: `Item-${saleItemWeekList.length + 1}`
            });
        }

        var salesQty_myChart = new Chart(salesQty_ctx, {
            type: "bar",
            data: {
                labels: ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"],
                datasets: [{
                        label: saleItemWeekList[0]['item_name'],
                        data: [topOneItemForMonday, topOneItemForTuesday, topOneItemForWednesday,
                            topOneItemForTursday, topOneItemForFriday, topOneItemForSaturday,
                            topOneItemForSunday
                        ],
                        backgroundColor: "#6f44d1",
                        stack: "Stack 0",
                    },
                    {
                        label: saleItemWeekList[1]['item_name'],
                        data: [topTwoItemForMonday, topTwoItemForTuesday, topTwoItemForWednesday,
                            topTwoItemForTursday, topTwoItemForFriday, topTwoItemForSaturday,
                            topTwoItemForSunday
                        ],
                        backgroundColor: "#e86813",
                        stack: "Stack 1",
                    },
                    {
                        label: saleItemWeekList[2]['item_name'],
                        data: [topThreeItemForMonday, topThreeItemForTuesday, topThreeItemForWednesday,
                            topThreeItemForTursday, topThreeItemForFriday, topThreeItemForSaturday,
                            topThreeItemForSunday
                        ],
                        backgroundColor: "#785374",
                        stack: "Stack 2",
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
            datasets: [{
                    label: "Sales",
                    data: [saleWeek['forMondayAmount'], saleWeek['forTuesdayAmount'], saleWeek[
                            'forWednesdayAmount'], saleWeek['forTursdayAmount'], saleWeek['forFridayAmount'],
                        saleWeek['forSaturdayAmount'], saleWeek['forSundayAmount']
                    ],
                    backgroundColor: ["#512DA8"],
                    borderColor: ["#6f44d1"],
                    tension: 0.4,
                },
                {
                    label: "Purchases",
                    data: [purchaseWeek['forPurchaseMondayAmount'], purchaseWeek['forPurchaseTuesdayAmount'],
                        purchaseWeek['forPurchaseWednesdayAmount'], purchaseWeek['forPurchaseTursdayAmount'],
                        purchaseWeek['forPurchaseFridayAmount'], purchaseWeek['forPurchaseSaturdayAmount'],
                        purchaseWeek['forPurchaseSundayAmount']
                    ],
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
    </script>
@endsection
