<template>
    <div>
        <b-link></b-link>

        <v-row>
            <v-col cols="12" md="6" sm="12">
                <v-card>
                    <div>
                        <apexchart
                            width="85%"
                            type="bar"
                            :options="options"
                            :series="series"
                        ></apexchart>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <v-card>
                    <div>
                        <apexchart
                            width="73%"
                            type="donut"
                            :options="d_options"
                            :series="d_series"
                        ></apexchart>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card>
                    <div>
                        <apexchart
                            width="95%"
                            type="line"
                            height="400"
                            :options="options"
                            :series="series"
                        ></apexchart>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import apexchart from "vue-apexcharts";
export default {
    components: {
        apexchart
    },
    data() {
        return {
            d_options: {},
            d_series: [44, 55, 41, 17, 15],
            options: {
                chart: {
                    id: "vuechart-example"
                },
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
                }
            },
            series: [
                {
                    name: "series-1",
                    data: [30, 40, 45, 50, 49, 60, 70, 91]
                }
            ]
        };
    }
};
</script>
