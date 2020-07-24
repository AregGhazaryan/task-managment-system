<template>
    <div class="container bg-white shadow-lg mt-4">
        <h1 class="pt-4 text-center">Stats</h1>
        <hr />
        <div style="height:350px; width:100%;">
            <canvas id="chart" style="width:100%;height:100%;"></canvas>
        </div>
    </div>
</template>
<script>
import Chart from "chart.js";

export default {
    data() {
        return {
            user_count: null
        };
    },
    created() {
        const userInfo = localStorage.getItem("user");
        const userData = JSON.parse(userInfo);
        axios
            .get("/api/users-count", {
                headers: {
                    Authorization: `Bearer ${userData.token}`
                }
            })
            .then(data => {
                var ctx = document.getElementById("chart");
                var myChart = new Chart(ctx, {
                    responsive: true,
                    maintainAspectRatio: false,
                    type: "bar",
                    data: {
                        labels: ["Stats"],
                        datasets: [
                            {
                                label: "# of Users",
                                data: [data.data.users],
                                backgroundColor: ["rgba(255, 99, 132, 0.2)"],
                                borderColor: ["rgba(255, 99, 132, 1)"],
                                borderWidth: 1
                            },
                            {
                                label: "Average # of Tasks",
                                data: [data.data.avg],
                                backgroundColor: ["rgba(54, 162, 235, 0.2)"],
                                borderColor: ["rgba(54, 162, 235, 1)"],
                                borderWidth: 1
                            },
                            {
                                label: "Average # of Tasks Assigned To Users",
                                data: [data.data.avg_per_usr],
                                backgroundColor: ["rgba(255, 206, 86, 0.2)"],
                                borderColor: ["rgba(255, 206, 86, 1)"],
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [
                                {
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }
                            ]
                        }
                    }
                });
            });
    },
};
</script>
