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
                        <v-form
                            ref="basic_information"
                            v-model="valid"
                            lazy-validation
                        >
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
                                v-model="formData.collection"
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

                            <v-select
                                :items="discount_type"
                                item-text="name"
                                item-value="value"
                                v-model="formData.discount_value"
                                outlined
                                dense
                                label="Discount Type"
                            ></v-select>

                            <v-text-field
                                label="Discount"
                                type="number"
                                outlined
                                dense
                                v-model="formData.discount"
                                placeholder="Discount"
                            ></v-text-field>

                            <div style="margin-top:20px">
                                <span
                                    style="font-weight:bold; margin-bottom:10px"
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
                                <span
                                    style="font-weight:bold; margin-bottom:10px"
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
                            <v-spacer></v-spacer>
                            <div class="text-right mt-5">
                                <v-btn
                                    color="primary"
                                    @click="updateBasicInformation"
                                    :loading="buttonLoading"
                                >
                                    <v-icon left dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                    update basic Information
                                    <template v-slot:loader>
                                        <span>Loading...</span>
                                    </template>
                                </v-btn>
                            </div>
                        </v-form>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        <div
                            v-for="(attr, index) in formData.variant"
                            :key="index"
                            style="border:1px solid black; padding:10px; margin-top:20px; margin-bottom:10px; border-radius:10px; position:relative"
                        >
                            <v-row>
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
                            </v-row>
                            <v-row>
                                <v-col v-if="attr.color">
                                    <v-combobox
                                        v-model="attr.sizes"
                                        label="Available Sizes"
                                        x-small-chips
                                        close
                                        multiple
                                        :rules="[required('Available Sizes')]"
                                        dense
                                        hint="Hit enter after putting each size"
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
                                                @click="select"
                                            >
                                                <strong>{{ item }}</strong
                                                >&nbsp;
                                            </v-chip>
                                        </template>
                                    </v-combobox>
                                </v-col>
                                <v-col class="text-right">
                                    <v-btn
                                        v-if="formData.variant.length > 1"
                                        dark
                                        color="error"
                                        ><v-icon>mdi-delete</v-icon
                                        >Delete</v-btn
                                    >
                                    <v-btn dark color="success"
                                        ><v-icon>mdi-update</v-icon
                                        >update</v-btn
                                    >
                                </v-col>
                            </v-row>
                            <!-- <v-btn
                                v-if="formData.variant.length > 1"
                                color="error"
                                @click="removeAttributes(index)"
                                fab
                                dark
                                small
                                absolute
                                top
                                right
                            >
                                <v-icon>mdi-delete</v-icon>
                            </v-btn> -->
                        </div>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        <div v-if="category_options.length > 0" class="row">
                            <v-col
                                v-for="(cat_opts, index) in category_options"
                                :key="index"
                                cols="12"
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
                                        v-model="formData.meta_tags"
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
                            <v-spacer></v-spacer>
                            <div class="text-right mt-5 mb-5">
                                <v-btn
                                    color="primary"
                                    @click="updateBasicInformation"
                                    :loading="buttonLoading"
                                >
                                    <v-icon left dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                    update basic Information
                                    <template v-slot:loader>
                                        <span>Loading...</span>
                                    </template>
                                </v-btn>
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
        removeMetaTags(item) {
            this.meta_tags.splice(this.meta_tags.indexOf(item), 1);
        },
        updateBasicInformation() {
            if (this.$refs.basic_information.validate()) {
                this.buttonLoading = true;

                axios
                    .put(`/api/product/${this.formData.id}`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.$router.push({ name: "Product" });
                        this.buttonLoading = false;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;

                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;
                    });
                this.buttonLoading = false;
            }
        },
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

                    var n = {};
                    this.formData.option_value.forEach((element, key) => {
                        n[element.option] = element.option_value;
                    });

                    this.option_values = n;
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
