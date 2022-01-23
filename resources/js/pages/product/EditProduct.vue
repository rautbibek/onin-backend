<template>
    <div>
        <!-- <b-link :items="breadcrumb"></b-link> -->

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
                <v-tab-item>
                    <v-card class="p-5 auto" flat>
                        <v-text-field
                            v-model="formData.title"
                            label="Product Name"
                            outlined
                            placeholder="Product Name Eg: Apple MacBook Pro13 M1 Chip with 8-Core CPU"
                            dense
                            :rules="[
                                required('Product Title'),
                                maxLength('Product Title', 200)
                            ]"
                            counter="200"
                        ></v-text-field>
                        <!-- <v-combobox
                            v-model="product_tags"
                            label="Product Tags"
                            x-small-chips
                            close
                            multiple
                            dense
                            outlined
                        >
                            <template
                                v-slot:selection="{
                                    attrs,
                                    item,
                                    select,
                                    selected
                                }"
                            >
                                <v-chip
                                    v-bind="attrs"
                                    :input-value="selected"
                                    close
                                    @click="select"
                                    @click:close="removerProductTags(item)"
                                >
                                    <strong>{{ item }}</strong
                                    >&nbsp;
                                </v-chip>
                            </template>
                        </v-combobox> -->
                        <v-text-field
                            v-model="formData.search_text"
                            label="Search Text"
                            outlined
                            dense
                            :rules="[
                                required('Search Text'),
                                maxLength('Search Text', 200)
                            ]"
                            counter="200"
                        ></v-text-field>
                        <v-autocomplete
                            v-model="formData.parent_id"
                            :items="categories"
                            :item-text="'name'"
                            :item-value="'id'"
                            label="Categories"
                            @change="getSubcategory"
                            :rules="[select('category')]"
                            outlined
                            dense
                        ></v-autocomplete>
                        <v-autocomplete
                            v-model="formData.category_id"
                            :items="subcategories"
                            :item-text="'name'"
                            :item-value="'id'"
                            label="Subcategories"
                            :rules="[select('category')]"
                            @change="getOptions"
                            outlined
                            dense
                        ></v-autocomplete>
                        <v-autocomplete
                            v-model="formData.brand_id"
                            :items="brands"
                            :item-text="'name'"
                            :item-value="'id'"
                            label="Brand"
                            :rules="[select('Brand')]"
                            outlined
                            dense
                        ></v-autocomplete>
                        <v-autocomplete
                            v-model="product_collection"
                            :items="collections"
                            :item-text="'name'"
                            :item-value="'id'"
                            label="Collection"
                            attach
                            chips
                            multiple
                            dense
                            outlined
                            clearable
                        >
                        </v-autocomplete>
                        <v-switch
                            class="text-right"
                            v-model="product_status"
                            :label="
                                product_status
                                    ? 'Product Status(Active)'
                                    : 'Product Status(Inactive)'
                            "
                            color="red"
                            inset
                            hide-details
                        ></v-switch>
                        <div style="margin-top:20px">
                            <span style="font-weight:bold; margin-bottom:10px"
                                >Short description :</span
                            >
                            <editor
                                api-key="mw953fmxqhyj06dim9f021ezz1q7vc9klgm46zhj4lahay02"
                                v-model="formData.short_description"
                                aria-placeholder="short description"
                                :init="{
                                    height: 200,
                                    menubar: false,
                                    plugins: [
                                        'advlist autolink lists link image charmap print preview anchor',
                                        'searchreplace visualblocks code fullscreen',
                                        'insertdatetime media table paste code help wordcount'
                                    ],
                                    toolbar:
                                        'undo redo | formatselect | bold italic backcolor | \
                                    alignleft aligncenter alignright alignjustify | \
                                    bullist numlist outdent indent | removeformat | help'
                                }"
                            />
                        </div>
                        <div style="margin-top:20px">
                            <span style="font-weight:bold; margin-bottom:10px"
                                >Description :</span
                            >
                            <editor
                                api-key="mw953fmxqhyj06dim9f021ezz1q7vc9klgm46zhj4lahay02"
                                v-model="formData.description"
                                :init="{
                                    height: 450,
                                    menubar: false,
                                    plugins: [
                                        'advlist autolink lists link image charmap print preview anchor',
                                        'searchreplace visualblocks code fullscreen',
                                        'insertdatetime media table paste code help wordcount'
                                    ],
                                    toolbar:
                                        'undo redo | formatselect | bold italic backcolor | \
                                    alignleft aligncenter alignright alignjustify | \
                                    bullist numlist outdent indent | removeformat | help'
                                }"
                            />
                        </div>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        <div>
                            <v-row
                                v-for="(attr, index) in formData.variant"
                                :key="index"
                            >
                                <v-col v-if="attr.color">
                                    <v-text-field
                                        v-model="attr.color"
                                        label="color"
                                        outlined
                                        dense
                                        disabled
                                    ></v-text-field>
                                </v-col>

                                <v-col>
                                    <v-text-field
                                        v-model="attr.quantity"
                                        type="number"
                                        label="Stock"
                                        outlined
                                        disabled
                                        dense
                                        :rules="[
                                            required('Stock'),
                                            maxLength('Stock', 200)
                                        ]"
                                    ></v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field
                                        v-model="attr.sku"
                                        label="sku"
                                        outlined
                                        disabled
                                        dense
                                        :rules="[
                                            required('SKU'),
                                            maxLength('SKU', 50)
                                        ]"
                                    ></v-text-field>
                                </v-col>

                                <v-col>
                                    <v-text-field
                                        v-model="attr.price"
                                        type="number"
                                        label="price"
                                        outlined
                                        disabled
                                        dense
                                        :rules="[
                                            required('Price'),
                                            maxLength('Price', 200)
                                        ]"
                                    ></v-text-field>
                                </v-col>

                                <v-col
                                    class="text-right"
                                    v-if="product_attribute_values.length > 1"
                                >
                                    <v-btn
                                        @click="removeAttributes(index)"
                                        fab
                                        dark
                                        outlined
                                        small
                                        color="error"
                                    >
                                        <v-icon dark>
                                            mdi-delete
                                        </v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </div>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        {{ category_options.length }}
                        <div v-if="category_options.length > 0" class="row">
                            <v-col
                                v-for="(cat_opts, index) in category_options"
                                :key="index"
                                cols="4"
                            >
                                <v-autocomplete
                                    v-model="option_values[cat_opts.key]"
                                    outlined
                                    :id="cat_opts.kay"
                                    :name="cat_opts.kay"
                                    :items="cat_opts.values"
                                    chips
                                    small-chips
                                    :rules="[select('category')]"
                                    Basic
                                    dense
                                    Information
                                    :label="cat_opts.code"
                                >
                                </v-autocomplete>
                            </v-col>
                        </div>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        <v-card-text>
                            <UploadImages @changed="handleImages"
                        /></v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat>
                        <v-card-text class="p-5 auto">
                            <div class="row">
                                <v-col cols="12" sm="12" md="6">
                                    <v-combobox
                                        v-model="meta_tags"
                                        label="Meta Keyword"
                                        x-small-chips
                                        close
                                        multiple
                                        dense
                                        outlined
                                    >
                                        <template
                                            v-slot:selection="{
                                                attrs,
                                                item,
                                                select,
                                                selected
                                            }"
                                        >
                                            <v-chip
                                                v-bind="attrs"
                                                :input-value="selected"
                                                close
                                                @click="select"
                                                @click:close="
                                                    removeMetaTags(item)
                                                "
                                            >
                                                <strong>{{ item }}</strong
                                                >&nbsp;
                                            </v-chip>
                                        </template>
                                    </v-combobox>
                                </v-col>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field
                                        v-model="formData.meta_title"
                                        label="Meta Title"
                                        outlined
                                        dense
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea
                                        counter
                                        outlined
                                        v-model="formData.meta_description"
                                        label="Meta Description"
                                    ></v-textarea>
                                </v-col>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </div>
</template>
<script>
import Editor from "@tinymce/tinymce-vue";
import UploadImages from "vue-upload-drop-images";
import { commonMixin } from "../../mixins/commonMixin";
import { ProductMixins } from "../../mixins/ProductMixins";
import { validation } from "../../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation, ProductMixins],
    components: {
        UploadImages,
        Editor
    },
    data() {
        return {
            product: [],
            formData: {},
            tab: "web",
            items: [
                "Basic Information",
                "Product Attributes",
                "Product Options",
                "Images",
                "SEO"
            ],
            errors: [],
            product_status: true,
            cat: "",

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
        getProduct() {
            axios
                .get(`/api/product/${this.$route.params.id}`)
                .then(result => {
                    this.product = result.data.data;
                    this.formData = this.product;
                    this.meta_tags = this.product.meta_tags;
                    this.getSubcategory();
                    this.getOptions();
                    this.getCollectionIds(this.formData.collection);
                    // this.formData.option_value.forEach((key, data) => {
                    //     this.option_values[data];
                    //     //console.log(key);
                    // });

                    console.log(this.option_values);
                    this.option_values = this.formData.option_value[0];
                })
                .catch(err => {
                    console.log(error.response.data.errors);
                });
        },
        getCollectionIds(collection) {
            this.product_collection = collection;
        }
    },
    mounted() {
        this.getProduct();
    },
    created() {
        //this.getCategory();
    }
};
</script>
