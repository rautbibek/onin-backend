<template>
    <div>
        <!-- <b-link :items="breadcrumb"></b-link> -->
        <v-card>
            <v-data-table
                :headers="headers"
                :items="users.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'users per page',
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
                <template v-slot:item.created_at="{ item }">
                    <span>{{ item.created_at }}</span>
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
                            >
                                <v-icon dark>
                                    mdi-eye
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>View </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon dark>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Edit </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="error"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon dark>
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Delete </span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
export default {
    data: () => ({
        search_keyword: "",
        title: "Users",
        dialog: false,
        loading: false,
        meta: [],
        users: [],
        formTitle: "Users",
        // breadcrumb: [
        //     {
        //         text: "Dashboard",
        //         disabled: false,
        //         href: "/dashboard"
        //     },
        //     {
        //         text: "Users",
        //         disabled: true,
        //         href: "/users"
        //     }
        // ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Name", value: "name", sortable: true },
            { text: "Email", value: "email" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/user?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.users = res.data;
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
