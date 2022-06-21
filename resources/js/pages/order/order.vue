<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="orders.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'orders per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="paginate"
            >
                <template v-slot:top>
                    <v-toolbar flat dense extended>
                        <v-toolbar-title>{{ title }} </v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-col cols="3" class="mt-5">
                            <v-autocomplete
                                v-model="filter_status"
                                class="mt-5"
                                label="Filter by status"
                                :items="statuses"
                                item-text="name"
                                item-value="value"
                                outlined
                                dense
                                clearable
                                @change="paginate($options)"
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="3" class="mt-5">
                            <v-text-field
                                v-model="search_keyword"
                                class="mt-5"
                                label="search"
                                outlined
                                dense
                                append-icon="search"
                                placeholder="Start typing..."
                                @click:append="paginate"
                                @blur="paginate"
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.delivery_charge="{ item }">
                    <span>Rs.{{ item.delivery_charge | formatNumber }}</span>
                </template>
                <template v-slot:item.total_price="{ item }">
                    <span>Rs.{{ item.total_price | formatNumber }}</span>
                </template>
                <template v-slot:item.payment_status="{ item }">
                    <v-icon v-if="item.payment_status" color="green" dark>
                        mdi-check-circle
                    </v-icon>
                    <v-icon v-else color="red" dark>
                        mdi-minus-circle
                    </v-icon>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span>{{
                        item.created_at | moment("calendar", "July 10 2011")
                    }}</span>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="view"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                :to="`/order/detail/${item.id}`"
                            >
                                <v-icon dark>
                                    mdi-eye
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>View order detail </span>
                    </v-tooltip>
                    <v-btn
                        x-small
                        fab
                        color="error"
                        dark
                        @click="deleteItem(item.id)"
                    >
                        <v-icon dark>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
            <iosAlertView></iosAlertView>
        </v-card>
    </div>
</template>
<script>
export default {
    name: "OrderComponent",

    data: () => ({
        search_keyword: "",
        title: "Orders",
        dialog: false,
        loading: false,
        filter_status: null,
        meta: [],
        orders: [],
        formTitle: "orders",
        statuses: [
            {
                name: "One",
                value: 1
            },
            {
                name: "Two",
                value: 2
            },
            {
                name: "Three",
                value: 3
            },
            {
                name: "Four",
                value: 4
            },
            {
                name: "Five",
                value: 5
            }
        ],
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Order",
                disabled: true,
                to: "/order"
            }
        ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Order ID", value: "order_identifier", sortable: true },

            { text: "Order BY", value: "order_by" },
            { text: "Delivery Charge", value: "delivery_charge" },
            { text: "Total Cost", value: "total_price" },
            { text: "Payment Type", value: "payment_type" },
            { text: "Payment Status", value: "payment_status" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/all/order?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword,
                        filter_status: this.filter_status
                    }
                })
                .then(res => {
                    this.orders = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        deleteItem(id) {
            // this.$toast.success("working", {
            //     timeout: 5000
            // });
            this.$iosConfirm("are you sure to want to delete this item?")
                .then(function() {
                    axios
                        .delete(`/api/remove/order/${id}`)
                        .then(res => {
                            this.paginate(this.$options);
                            this.$toast.success(res.data.message, {
                                timeout: 5000
                            });
                        })
                        .catch(error => {
                            this.$toast.error(error.response.data.message, {
                                timeout: 5000
                            });
                        });
                })
                .catch(function() {
                    alert("error occoured");
                });

            //this.$iosConfirm("confirm?");
        }
    }
};
</script>
<style scoped>
a {
    text-decoration: none;
}
</style>
