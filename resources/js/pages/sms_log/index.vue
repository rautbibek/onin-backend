<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="sms_logs.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'sms log per page',
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

                        <v-col cols="4" class="mt-5">
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
                            ></v-text-field>
                        </v-col>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.code="{ item }">
                    <span v-if="item.code">{{ item.code }}</span>
                    <span v-else>-</span>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span>{{
                        item.created_at | moment("ddd, MMMM Do YYYY")
                    }}</span>
                </template>
                <template v-slot:item.phone="{ item }">
                    <span v-if="item.phone"
                        ><a :href="`tel:${item.phone}`">{{
                            item.phone
                        }}</a></span
                    >
                    <span v-else>-</span>
                </template>
                <template v-slot:item.cost="{ item }">
                    <span style="color:green" v-if="item.status">
                        Rs.{{ priceCalculation(item.message.length) }}
                    </span>
                    <span v-else>-</span>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
import { FLOWDECLARATION_TYPES } from "@babel/types";

export default {
    data: () => ({
        search_keyword: "",
        title: "SMS Log",
        dialog: false,
        loading: false,
        meta: [],
        sms_logs: [],
        formTitle: "SMS Log",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "SMs Log",
                disabled: true,
                to: "/sms/log"
            }
        ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "SMS Type", value: "sms_type", sortable: true },
            { text: "Contact Number", value: "mobile", sortable: false },
            { text: "Title", value: "title", sortable: false },
            { text: "SMS", value: "message", sortable: false },
            { text: "IP Address", value: "ip_address", sortable: false },
            { text: "Code", value: "code", sortable: false },

            { text: "Cost", value: "cost", sortable: false },
            { text: "Created At", value: "created_at" }
        ]
    }),

    methods: {
        priceCalculation(sms_length) {
            return Math.ceil(sms_length / 160) * 2;
        },
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/sms/log?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.sms_logs = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        }
    }
};
</script>
