<template>
    <div>
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
    </div>
</template>

<script>
import apexchart from "vue-apexcharts";
export default {};
</script>

<style lang="scss" scoped></style>
