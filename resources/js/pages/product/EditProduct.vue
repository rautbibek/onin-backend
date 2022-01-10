<template>
    <div>
        <b-link :items="breadcrumb"></b-link>

        <v-card>
            <v-toolbar color="primary" dark flat dense>
                <template v-slot:extension>
                    <v-tabs v-model="tab" align-with-title>
                        <v-tabs-slider color="yellow"></v-tabs-slider>

                        <v-tab v-for="item in items" :key="item">
                            {{ item }}
                        </v-tab>
                    </v-tabs>
                </template>
            </v-toolbar>

            <v-tabs-items v-model="tab">
                <v-tab-item v-for="item in items" :key="item">
                    <v-card flat>
                        {{ tab }}
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </div>
</template>
<script>
export default {
    data() {
        return {
            tab: "web",
            items: ["web", "shopping", "videos", "images", "news"],
            text:
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit,  et dolore mased do eiusmod tempor incididunt ut laboregna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",

            breadcrumb: [
                {
                    text: "Dashboard",
                    disabled: false,
                    to: "/dashboard"
                },
                {
                    text: "Product",
                    disabled: false,
                    to: "/product"
                },
                {
                    text: "Edit Product",
                    disabled: true,
                    to: "/product"
                }
            ]
        };
    },
    methods: {
        dd() {
            var a = [1, 2];
        }
    }
};
</script>
