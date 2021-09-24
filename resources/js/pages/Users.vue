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
import { mapGetters, mapActions } from "vuex";
export default {
    data: () => ({
        search_keyword: "",
        title: "Users",
        dialog: false,

        dialogDelete: false,
        loading: false,
        meta: [],
        users: [],
        formTitle: "Users",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                href: "/dashboard"
            },
            {
                text: "Users",
                disabled: true,
                href: "/users"
            }
        ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: true },
            { text: "Name", value: "name", sortable: true },
            { text: "Email", value: "email" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ],

        editedIndex: -1,
        editedItem: {
            name: "",
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0
        }
    }),

    computed: mapGetters(["allUsers"]),

    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        }
    },

    created() {
        //this.initialize();
        this.fetchUsers();
    },

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/user?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sort_by: e.sortBy,
                        order_by_desc: e.sortDesc,
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
        },

        ...mapActions(["fetchUsers"]),

        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },

        deleteItemConfirm() {
            this.desserts.splice(this.editedIndex, 1);
            this.closeDelete();
        },

        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem);
            } else {
                this.desserts.push(this.editedItem);
            }
            this.close();
        }
    }
};
</script>
