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
                            <v-card-title>
                                <v-row align="center" class="mx-0">
                                    <v-rating
                                        v-model="rating"
                                        color="amber"
                                        dense
                                        half-increments
                                        size="44"
                                    ></v-rating>

                                    <div class="grey--text ms-4">
                                        4.5 (413)
                                    </div>
                                </v-row>
                            </v-card-title>

                            <v-card-text
                                class="text--primary "
                                v-html="detail.short_desc"
                            ></v-card-text>
                            <v-card-title
                                style="color:red !important"
                                color="primary"
                                class="mb-3"
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
                            <v-divider></v-divider>
                            <v-card-text class="my-0 py-0">
                                <div class="row">
                                    <div class="col-1">SKU :</div>
                                    <div class="col-2">
                                        {{ detail.variant[0].sku }}
                                    </div>
                                    <div class="col-2">Total Stock :</div>
                                    <div class="col-1">
                                        {{ detail.inventory_track }}
                                    </div>
                                    <div class="col-4">
                                        Current Variant Stock :
                                    </div>
                                    <div class="col-2">
                                        {{ detail.variant[0].quantity }}
                                    </div>
                                </div>
                            </v-card-text>

                            <v-divider></v-divider>

                            <v-card-title>Available Colors</v-card-title>
                            <v-card-text>
                                <v-chip-group
                                    v-model="selection"
                                    active-class="deep-purple accent-4 white--text"
                                    column
                                >
                                    <v-chip>Red</v-chip>

                                    <v-chip>Green</v-chip>

                                    <v-chip>Blue</v-chip>

                                    <v-chip>Yellow</v-chip>
                                </v-chip-group>
                            </v-card-text>
                        </v-col>
                    </v-row>
                    <v-card flat class="mt-5 mb-5">
                        <v-tabs v-model="tab" color="deep-purple accent-4">
                            <v-tab href="#description">Description</v-tab>
                            <v-tab href="#detail">Detail</v-tab>
                            <v-tab href="#test">Test</v-tab>

                            <v-tabs-items v-model="tab">
                                <v-tab-item value="description">
                                    <v-card class="mt-3" flat>
                                        <v-card-text
                                            class="text--primary p-0 m-0"
                                            v-html="detail.description"
                                        ></v-card-text>
                                    </v-card>
                                </v-tab-item>
                                <v-tab-item value="detail">
                                    <v-card flat>
                                        <v-card-text class="text--primary">
                                            <v-row
                                                v-for="item in detail.option_values"
                                                :key="item.id"
                                            >
                                                <v-col cols="4">{{
                                                    item.option
                                                }}</v-col>
                                                <v-col cols="1">-</v-col>
                                                <v-col cols="4">{{
                                                    item.option_value
                                                }}</v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                                <v-tab-item value="test">
                                    <v-card flat>
                                        <v-card-text>test</v-card-text>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs-items>
                        </v-tabs>
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
            tab: "description",
            notifications: false,
            sound: true,
            widgets: false,
            rating: 4.5,
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
