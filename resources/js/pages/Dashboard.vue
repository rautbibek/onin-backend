<template>
    <div>
        <b-link></b-link>
        <v-row v-if="loading" class="mt-3">
            <v-col cols="12" sm="12" md="3" xs="12" v-for="n in 4" :key="n">
                <v-skeleton-loader
                    class="mx-auto"
                    max-width="300"
                    height="100"
                    type="card"
                ></v-skeleton-loader>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <v-skeleton-loader
                    class="mx-auto"
                    type="image"
                ></v-skeleton-loader>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <v-skeleton-loader
                    class="mx-auto"
                    type="image"
                ></v-skeleton-loader>
            </v-col>
            <v-col cols="12">
                <v-skeleton-loader
                    class="mx-auto"
                    type="image"
                ></v-skeleton-loader>
            </v-col>
        </v-row>
        <!-- <v-skeleton-loader
            class="mx-auto"
            max-width="300"
            height="100"
            type="card"
        ></v-skeleton-loader> -->
        <div v-if="!loading">
            <v-row class="mt-3">
                <v-col class="py-0" cols="12" md="3" lg="3" sm="6" xs="12">
                    <v-alert
                        color="#385F73"
                        dark
                        icon="mdi-account-circle"
                        border="left"
                        prominent
                    >
                        <div class="text-h6">
                            Total Users
                        </div>
                        <div>
                            {{ counter.total_users | formatNumber }}
                        </div>
                    </v-alert>
                </v-col>
                <v-col class="py-0" cols="12" md="3" lg="3" sm="6" xs="12">
                    <v-alert
                        color="#952175"
                        dark
                        icon="inventory"
                        border="left"
                        prominent
                    >
                        <div class="text-h6">
                            Active Products
                        </div>
                        <div>
                            {{ counter.active_products | formatNumber }}
                        </div>
                    </v-alert>
                </v-col>
                <v-col class="py-0" cols="12" md="3" lg="3" sm="6" xs="12">
                    <v-alert
                        color="green"
                        dark
                        icon="mdi-cash"
                        border="left"
                        prominent
                    >
                        <div class="text-h6">
                            Total Revenue
                        </div>
                        <div>NPR. {{ counter.revenue | formatNumber }}</div>
                    </v-alert>
                </v-col>
                <v-col class="py-0" cols="12" md="3" lg="3" sm="6" xs="12">
                    <v-alert
                        color="primary"
                        dark
                        icon="mdi-vuetify"
                        border="left"
                        prominent
                    >
                        <div class="text-h6">
                            Active Products
                        </div>
                        <div>
                            {{ counter.active_products | formatNumber }}
                        </div>
                    </v-alert>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="6" sm="12" xs="12">
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
                        <PieChart />
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
    </div>
</template>
<script>
import apexchart from "vue-apexcharts";
import PieChart from "./report/PieChart.vue";
export default {
    components: {
        apexchart,
        PieChart
    },
    data() {
        return {
            loading: false,
            options: {},
            series: [44, 55, 41, 17, 15],
            counter: {},
            sales: [],
            options: {
                xaxis: {
                    categories: []
                }
            },
            series: [
                {
                    data: []
                }
            ]
        };
    },
    methods: {
        getSalesReport() {
            this.loading = true;
            axios
                .get(`/api/sales/report`)
                .then(res => {
                    this.sales = res.data;
                    this.sales.forEach(e => {
                        this.options.xaxis.categories.push(e.date);

                        this.series[0].data.push(e.TOTAL_SALES);
                        this.loading = false;
                    });
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        getRecordCounter() {
            this.loading = true;
            axios
                .get("/api/record/counter")
                .then(res => {
                    this.counter = res.data;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        }
    },
    created() {
        this.getSalesReport();
        this.getRecordCounter();
    }
};
</script>
