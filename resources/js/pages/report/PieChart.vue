<template>
    <div>
        <apexchart
            type="pie"
            width="560"
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
            series: [],
            chartOptions: {
                chart: {
                    width: 480,
                    type: "pie"
                },
                labels: [],
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
    },
    methods: {
        getChartData() {
            axios
                .get(`/api/status/report`)
                .then(res => {
                    Object.keys(res.data).forEach(key => {
                        this.series.push(res.data[key]);
                        this.chartOptions.labels.push(key.split("_").join(" "));
                    });
                })
                .catch();
        }
    },
    created() {
        this.getChartData();
    }
};
</script>

<style lang="scss" scoped></style>
