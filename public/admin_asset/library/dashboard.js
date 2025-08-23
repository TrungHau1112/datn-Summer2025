(function ($) {
    "use strict";
    var TGNT = {};

    TGNT.getOrdersAndRevenueByYear = () => {
        $.ajax({
            url: "/admin/dashboard/ajax/getOrdersAndRevenueByYear",
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                
            },
            success: function (res) {
                const months = res.data.original.map(
                    (item) => `Tháng ${item.month}`
                );
                const totalOrders = res.data.original.map(
                    (item) => item.total_orders
                );
                const monthlyRevenue = res.data.original.map(
                    (item) => item.monthly_revenue
                );
    
                const ctx = document
                    .getElementById("ordersChart")
                    .getContext("2d");
    
                const ordersChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: "Số đơn hàng",
                                data: totalOrders,
                                backgroundColor: "rgba(75, 192, 192, 0.6)", 
                                borderColor: "rgba(75, 192, 192, 1)",
                                borderWidth: 2, 
                                borderRadius: 10,
                                hoverBackgroundColor: "rgba(75, 192, 192, 0.8)", 
                                hoverBorderColor: "rgba(75, 192, 192, 1)", 
                            },
                            {
                                label: "Doanh thu (VNĐ)",
                                data: monthlyRevenue,
                                backgroundColor: "rgba(255, 99, 132, 0.6)", 
                                borderColor: "rgb(255, 99, 132)", 
                                tension: 0.4,
                                borderWidth: 2,
                                type: "line",
                                yAxisID: "y2",
                                pointStyle: "circle",
                                pointRadius: 6,
                                pointBackgroundColor: "rgb(255, 99, 132)", 
                                pointHoverRadius: 8, 
                                pointHoverBackgroundColor: "rgb(255, 99, 132)", 
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: "Số đơn hàng và doanh thu theo tháng",
                            font: {
                                size: 20,
                                family: "'Roboto', sans-serif", // Font chữ đẹp
                            },
                            padding: 20, // Khoảng cách xung quanh tiêu đề
                        },
                        legend: {
                            position: "top", // Vị trí của legend
                            labels: {
                                font: {
                                    size: 14,
                                    family: "'Roboto', sans-serif",
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Số đơn hàng",
                                    font: {
                                        size: 16,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                                ticks: {
                                    stepSize: 5,
                                    font: {
                                        size: 12,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                            },
                            y2: {
                                beginAtZero: true,
                                position: "right",
                                title: {
                                    display: true,
                                    text: "Doanh thu (VNĐ)",
                                    font: {
                                        size: 16,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                                grid: {
                                    drawOnChartArea: false, // Ẩn lưới cho trục y2
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                            },
                        },
                        tooltips: {
                            mode: "index",
                            intersect: false,
                            backgroundColor: "rgba(0, 0, 0, 0.7)", // Màu nền của tooltip
                            titleFontSize: 14,
                            bodyFontSize: 12,
                        },
                        animation: {
                            duration: 1000, // Thời gian cho hiệu ứng
                            easing: "easeInOutQuart", // Kiểu chuyển động
                        },
                    },
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + textStatus + " " + errorThrown);
            },
        });
    };
    

    TGNT.newCustomersByMonth = () => {
        $.ajax({
            url: "/admin/dashboard/ajax/newCustomersByMonth",
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                
            },
            success: function (res) {
                const months = res.data.map(
                    (item) => `Tháng ${item.month}`
                );
                const newCustomers = res.data.map(
                    (item) => item.new_customers
                );
    
                const ctx = document
                    .getElementById("newCustomersChart")
                    .getContext("2d");
    
                const newCustomersChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: "Khách hàng mới",
                                data: newCustomers,
                                backgroundColor: "rgba(75, 192, 192, 0.6)", 
                                borderColor: "rgba(75, 192, 192, 1)", 
                                borderWidth: 2, 
                                borderRadius: 10, 
                                hoverBackgroundColor: "rgba(75, 192, 192, 0.8)", 
                                hoverBorderColor: "rgba(75, 192, 192, 1)"
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: "Số khách hàng mới theo từng tháng",
                            font: {
                                size: 20,
                                family: "'Roboto', sans-serif", 
                            },
                            padding: 20,
                        },
                        legend: {
                            position: "top", 
                            labels: {
                                font: {
                                    size: 14,
                                    family: "'Roboto', sans-serif",
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Số khách hàng mới",
                                    font: {
                                        size: 16,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                                ticks: {
                                    stepSize: 5,
                                    font: {
                                        size: 12,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: "Tháng",
                                    font: {
                                        size: 16,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        family: "'Roboto', sans-serif",
                                    },
                                },
                            },
                        },
                        tooltips: {
                            mode: "index", 
                            intersect: false, 
                            backgroundColor: "rgba(0, 0, 0, 0.7)",
                            titleFontSize: 14,
                            bodyFontSize: 12,
                        },
                        animation: {
                            duration: 1000,
                            easing: "easeInOutQuart",
                        },
                    },
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + textStatus + " " + errorThrown);
            },
        });
    };
    


    $(document).ready(function () {
        TGNT.getOrdersAndRevenueByYear();
        TGNT.newCustomersByMonth()
    });
})(jQuery);
