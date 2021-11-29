<template>
    <div>
        <v-card class="mx-auto">
            <v-toolbar flat>
                <v-toolbar-title>{{ title }}</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn
                    class="ma-2"
                    @click="openDialog"
                    fab
                    x-small
                    color="success"
                >
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
                <v-btn fab x-small color="primary">
                    <v-icon>mdi-refresh</v-icon>
                </v-btn>
            </v-toolbar>

            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">
                                ID
                            </th>
                            <th class="text-left">
                                Category Name
                            </th>
                            <th class="text-left">
                                Subcategories
                            </th>
                            <th class="text-left">
                                Created Date
                            </th>
                            <th class="text-left">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in menu" :key="item.name">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>
                                <v-card
                                    v-if="item.children.length > 0"
                                    flat
                                    class="my-5"
                                >
                                    <v-list>
                                        <v-list-item
                                            v-for="scat in item.children"
                                            :key="scat.name"
                                        >
                                            <v-list-item-avatar>
                                                <v-icon class="grey" dark>
                                                    arrow_right_alt
                                                </v-icon>
                                            </v-list-item-avatar>

                                            <v-list-item-content>
                                                <v-list-item-title
                                                    v-text="scat.name"
                                                ></v-list-item-title>

                                                <v-list-item-subtitle>
                                                    <span>{{
                                                        scat.created_at
                                                            | moment("calendar")
                                                    }}</span>
                                                </v-list-item-subtitle>
                                            </v-list-item-content>

                                            <v-list-item-action>
                                                <v-btn
                                                    @click="editItem(scat)"
                                                    small
                                                    icon
                                                >
                                                    <v-icon
                                                        small
                                                        color="primary"
                                                        >mdi-pencil</v-icon
                                                    >
                                                </v-btn>
                                                <v-btn
                                                    @click="deleteItem(scat)"
                                                    small
                                                    icon
                                                >
                                                    <v-icon small color="red"
                                                        >mdi-delete</v-icon
                                                    >
                                                </v-btn>
                                            </v-list-item-action>
                                        </v-list-item>
                                    </v-list>
                                </v-card>
                                <div v-else>No submenu</div>
                            </td>
                            <td>
                                <span>{{
                                    item.created_at | moment("ddd, hA")
                                }}</span>
                            </td>
                            <td>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            x-small
                                            fab
                                            color="primary"
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="editItem(item)"
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
                                            @click="deleteItem(item)"
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
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Category</span>
                    <v-spacer></v-spacer>
                    <v-icon left @click="closeModel">close</v-icon>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <ValidationErrors :errors="errors"></ValidationErrors>
                    <v-container> </v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-autocomplete
                            v-model="formData.parent_id"
                            :items="fetAllCategories"
                            :item-text="'name'"
                            :item-value="'id'"
                            outlined
                            label="Root category"
                        ></v-autocomplete>

                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('category name')]"
                            label="Category Name"
                            required
                            outlined
                        ></v-text-field>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="formData.icon"
                                    :rules="[required('Icon')]"
                                    label="icon"
                                    required
                                    outlined
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-file-input
                                    v-model="formData.image"
                                    color="deep-purple accent-4"
                                    counter
                                    label="Cover Image"
                                    placeholder="Select your files"
                                    prepend-icon="mdi-camera"
                                    outlined
                                    show-size
                                    accept="image/*"
                                >
                                    <template
                                        v-slot:selection="{ index, text }"
                                    >
                                        <v-chip
                                            v-if="index < 2"
                                            color="deep-purple accent-4"
                                            dark
                                            label
                                            small
                                        >
                                            {{ text }}
                                        </v-chip>

                                        <span
                                            v-else-if="index === 2"
                                            class="text-overline grey--text text--darken-3 mx-2"
                                        >
                                            +{{ files.length - 2 }} File(s)
                                        </span>
                                    </template>
                                </v-file-input>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="closeModel" color="error">
                        <v-icon left>mdi-cancel</v-icon>
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        @click="submit"
                        :loading="buttonLoading"
                    >
                        <v-icon left>save</v-icon>
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import { commonMixin } from "../mixins/commonMixin";
import { validation } from "../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation],
    data: () => ({
        search_keyword: "",
        title: "Categories",
        dialog: false,
        loading: false,
        meta: [],
        users: [],
        formTitle: "Categories",
        dialog: false,
        image: [],
        menu: {}
    }),

    computed: mapGetters(["fetAllCategories"]),

    methods: {
        getMenu() {
            this.loading = true;
            axios
                .get(`/api/category`)
                .then(res => {
                    this.menu = res.data;
                })
                .catch(error => {
                    this.loading = false;
                });
        },

        deleteItem(item) {
            let confirmAction = confirm(
                "Are you sure to want to delete this ?"
            );
            if (confirmAction) {
                axios
                    .delete(`api/category/${item.id}`)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 5000
                        });
                        //this.paginate(this.$options);
                        this.getCategories();
                        this.getMenu();
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.error, {
                            timeout: 2000
                        });
                    });
            }
        },
        editItem(item) {
            this.formData = item;
            this.openDialog();
        },

        submit() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post("api/category", this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;
                        this.closeModel();
                        //this.paginate(this.options);
                        this.getCategories();
                        this.getMenu();
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;

                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;
                    });
            }
        },

        ...mapActions(["getCategories"])
    },

    created() {
        this.getCategories();
        this.getMenu();
    }
};
</script>
