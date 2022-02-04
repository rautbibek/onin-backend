<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card class="mx-auto">
            <v-data-table
                :headers="headers"
                :items="menu.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'category per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getMenu"
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
                                @click:append="getMenu"
                                @blur="getMenu"
                            ></v-text-field>
                        </v-col>
                        <div class="mt-1">
                            <v-btn
                            class="ma-2"
                            @click="openDialog"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn @click="getMenu" fab x-small color="primary">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                        </div>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.root="{ item }">
                    <span v-if="item.parent">
                        <span v-if="item.parent.parent">{{ item.parent.parent.name }}</span>
                        <span v-else>N/A</span>
                    </span>
                    <span v-else>N/A</span>
                </template>
                <template v-slot:item.sub_root="{ item }">
                    <span v-if="item.parent">{{ item.parent.name }}</span>
                        <span v-else>N/A</span>
                </template>
                <template v-slot:item.child="{ item }">
                    <span>{{ item.name }}</span>
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
                                color="teal"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                @click="openOptionDialog(item)"
                            >
                                <v-icon dark>
                                    mdi-cog
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Category Oprion </span>
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
                </template>
            </v-data-table>
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
                            item-text="name"
                            item-value="id"
                            outlined
                            label="Root category"
                        >
                        <template v-slot:selection="data">
                            <v-chip
                            v-bind="data.attrs"
                            :input-value="data.selected"
                            
                            @click="data.select"
                            
                            >
                            <span v-if="data.item.parent">{{data.item.parent.name}} -></span>{{ data.item.name }} 
                            </v-chip>
                        </template>
                        <template v-slot:item="data">
                            
                            <template>
                            <v-list-item-content>
                                <v-list-item-title ><span v-if="data.item.parent">{{data.item.parent.name}} -></span> {{data.item.name}}</v-list-item-title>
                                <v-list-item-subtitle ></v-list-item-subtitle>
                            </v-list-item-content>
                            </template>
                        </template>

                        </v-autocomplete>

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
        <v-dialog
            v-model="optionDialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
        >
        <v-card tile>
          <v-toolbar
            flat
            dark
            color="primary"
            dense
          >
            <v-toolbar-title><strong>{{category_name}} </strong>  option setting </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn
              icon
              dark
              @click="optionDialog = false"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-list
              three-line
              subheader
            >
              <v-subheader>Category Option Controls</v-subheader>
              <v-list-item>
                <v-list-item-content>
                 <v-list-item-subtitle>Choose if this category contains color attribute</v-list-item-subtitle>
                  <v-list-item-action>
                      <v-switch
                        v-model="has_color"
                        label="Has Color"
                        color="red"                      
                        hide-details
                        ></v-switch>
                    </v-list-item-action>
                    
                     <v-list-item-subtitle>Choose if this category contains sizes attribute</v-list-item-subtitle>
                  <v-list-item-subtitle>
                      <v-switch
                        v-model="has_size"
                        label="Has Sizes"
                        color="red"
                        hide-details
                        ></v-switch>
                    </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-subheader>Choose your options</v-subheader>
            <v-row no-gutters>
                <v-col md="4" sm="12" class="pa-4" v-for="(option,index) in options" :key="index" cols="12">
                    <!-- <v-list
                    three-line
                    subheader
                    > -->
                     <v-checkbox :value="option.id" :label="option.code" v-model="category_options"></v-checkbox>
                    <!-- <v-list-item >
                        <v-list-item-action>
                        <v-checkbox :value="option.id"  v-model="category_options"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                        <v-list-item-title>{{option.code}}</v-list-item-title>
                        
                        <v-list-item-subtitle class="pa-1" v-for="op in option.values" :key="op">
                            - {{op}}
                        </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item> -->
                    
                    <!-- </v-list> -->
                </v-col>
            </v-row>
            <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="optionDialog = false" color="error">
                        <v-icon left>mdi-cancel</v-icon>
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        @click="updateCategoryOptions"
                        :loading="buttonLoading"
                    >
                        <v-icon left>save</v-icon>
                        proceed
                    </v-btn>
                </v-card-actions>
          </v-card-text>

          <div style="flex: 1 1 auto;"></div>
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
        id:null,
        category_name:'',
        loading: false,
        optionDialog:false,
        has_color:false,
        has_size:false,
        options:[],
        category_options:[],
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Category",
                disabled: true,
                to: "/category"
            }
        ],
        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Root", value: "root", sortable: false },
            { text: "Sub Root", value: "sub_root" },
            { text: "Children", value: "child" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ],
        meta: [],
        
        formTitle: "Categories",
        dialog: false,
        image: [],
        menu: {}
    }),

    computed: mapGetters(["fetAllCategories"]),

    methods: {
        getMenu(e) {
            this.loading = true;
            axios
                .get(`/api/category?page=${e.page}`,{
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.menu = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },

        getOptions(){
            axios.get('all/options').then(res=>{
                this.options = res.data;
                console.log(this.options);
            }).catch(error=>{
                console.log(error.response.data.errors);
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
                        this.getMenu(this.$options);
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
                        
                        this.getCategories();
                        this.getMenu(this.$options);
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

        openOptionDialog(item){
            
            this.id = item.id;
            this.category_name = item.name
            this.has_color = item.has_color;
            this.has_size  = item.has_size,
            this.category_options = item.cat_options;
            this.optionDialog = true;
        },

        updateCategoryOptions(){
            axios.post(`api/update/category/options`,{
                id: this.id,
                has_color: this.has_color,
                has_size : this.has_size,
                category_options:this.category_options,
            }).then(res=>{
                console.log(res.data);
                this.optionDialog = false;
                this.$toast.success(res.data.message, {
                    timeout: 2000
                });
            }).catch(error=>{
                this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
            });
        },

        ...mapActions(["getCategories"])
    },
    mounted(){
        this.getOptions();
    },

    created() {
        this.getCategories();
        this.getMenu();
    }
};
</script>
