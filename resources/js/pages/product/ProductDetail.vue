<template>
    <v-row justify="center">
        <v-dialog
            v-model="product_detail_dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
        >
            <v-card>
                <slot name="toolbar"></slot>

                <v-card-text class="mt-5">
                    <v-row>
                        <v-col cols="6">
                            <v-carousel :show-arrows="false">
                                <v-carousel-item
                                    v-for="(item, i) in items"
                                    :key="i"
                                    :src="item.src"
                                ></v-carousel-item>
                            </v-carousel>
                        </v-col>
                        <v-col cols="6">
                            <h3 class="text-h5 text--primary">
                                {{ detail.title }}
                            </h3>
                            <v-card-title
                                style="color:red !important"
                                color="primary"
                                class="p-0 m-0 mb-3"
                            >
                                Rs.{{ detail.variant[0].price }}.00 &nbsp;
                                &nbsp;
                                <span
                                    style="color:green"
                                    v-if="detail.variant[0].quantity > 0"
                                >
                                    <strong>(In Stock)</strong></span
                                >
                                <span style="color:red" v-else>
                                    <strong>(Out Of Stock)</strong>
                                </span>
                            </v-card-title>

                            <v-card-text
                                class="text--primary p-0 m-0"
                                v-html="detail.short_desc"
                            ></v-card-text>
                            <div class="row">
                                <div class="col-1">SKU :</div>
                                <div class="col-2">
                                    {{ detail.variant[0].sku }}
                                </div>
                                <div class="col-2">Total Stock :</div>
                                <div class="col-1">
                                    {{ detail.inventory_track }}
                                </div>
                                <div class="col-4">Current Variant Stock :</div>
                                <div class="col-2">
                                    {{ detail.variant[0].quantity }}
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                    <v-card>
                        <v-toolbar flat>
                            <template v-slot:extension>
                                <v-tabs v-model="tabs" fixed-tabs>
                                    <v-tabs-slider></v-tabs-slider>
                                    <v-tab
                                        href="#mobile-tabs-5-1"
                                        class="primary--text"
                                    >
                                        <v-icon>mdi-phone</v-icon>
                                    </v-tab>

                                    <v-tab
                                        href="#mobile-tabs-5-2"
                                        class="primary--text"
                                    >
                                        <v-icon>mdi-heart</v-icon>
                                    </v-tab>

                                    <v-tab
                                        href="#mobile-tabs-5-3"
                                        class="primary--text"
                                    >
                                        <v-icon>mdi-account-box</v-icon>
                                    </v-tab>
                                </v-tabs>
                            </template>
                        </v-toolbar>

                        <v-tabs-items v-model="tabs">
                            <v-tab-item
                                v-for="i in 3"
                                :key="i"
                                :value="'mobile-tabs-5-' + i"
                            >
                                <v-card flat>
                                    <v-card-text v-text="text"></v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<script>
export default {
    props: {
        product_detail_dialog: {
            type: Boolean,
            default: false
        },
        detail: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            notifications: false,
            sound: true,
            widgets: false,
            items: [
                {
                    src:
                        "https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/sky.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/bird.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/planet.jpg"
                }
            ],
            tabs: null,
            text:
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."
        };
    }
};
</script>
