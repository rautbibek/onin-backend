<template>
    <div>
        <apexchart
            type="pie"
            width="380"
            :options="chartOptions"
            :series="series"
        ></apexchart>
    </div>
</template>

<script>
import apexchart from "vue-apexcharts";
export default {
    name: "PieChart",
    components: {
        apexchart
    },
    data() {
        return {
            series: [44, 55, 13, 43, 22],
            chartOptions: {
                chart: {
                    width: 380,
                    type: "pie"
                },
                labels: ["Team A", "Team B", "Team C", "Team D", "Team E"],
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 300
                            },
                            legend: {
                                position: "bottom"
                            }
                        }
                    }
                ]
            }
        };
    }
};
</script>

<style lang="scss" scoped></style>
