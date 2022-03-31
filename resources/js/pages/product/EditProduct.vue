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
                        <div style="text-align:center">
                            <label for="cover" class="cover-input">
                                <img
                                    class="cover-img"
                                    v-if="url"
                                    :src="url"
                                    alt="cover"
                                />
                                <v-icon class="icon" color="black" large>
                                    mdi-camera
                                </v-icon>
                            </label>
                            <input
                                class="cover"
                                @change="filePreview"
                                id="cover"
                                type="file"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                            />
                        </div>
                        <p v-if="cover_error" class="text-center text-danger">
                            <small v-if="cover_error.cover" style="color:red">{{
                                cover_error.cover[0]
                            }}</small>
                        </p>
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
                                        @click="confirmDialog(attr)"
                                        ><v-icon>mdi-delete</v-icon
                                        >Delete</v-btn
                                    >
                                    <v-btn
                                        @click="editAttribute(attr)"
                                        dark
                                        color="primary"
                                        ><v-icon>mdi-pencil</v-icon>EDIT</v-btn
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
                        <div class="text-center">
                            <v-btn @click="addAttribute" color="success">
                                <v-icon left>mdi-plus</v-icon> Add New
                                Variant</v-btn
                            >
                        </div>
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    <v-card flat class="p-5 auto">
                        <v-form
                            ref="update_options"
                            v-model="option_valid"
                            lazy-validation
                        >
                            <div v-if="category_options.length > 0" class="row">
                                <v-col
                                    v-for="(cat_opts,
                                    index) in category_options"
                                    :key="index"
                                    cols="12"
                                >
                                    <v-autocomplete
                                        v-if="cat_opts.type == 'select'"
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
                                    <v-text-field
                                        v-if="cat_opts.type == 'input'"
                                        :label="cat_opts.name"
                                        outlined
                                        dense
                                        :rules="[required(cat_opts.code)]"
                                        clearable
                                        v-model="option_values[cat_opts.key]"
                                        :placeholder="cat_opts.code"
                                    ></v-text-field>
                                </v-col>
                            </div>
                            <v-spacer></v-spacer>
                            <div class="text-right mt-5 mb-5">
                                <v-btn
                                    color="primary"
                                    @click="updateProductOptions"
                                    :loading="optionButtonLoading"
                                    :disabled="optionButtonLoading"
                                >
                                    <v-icon left dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                    Update product options
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
                        <v-card-subtitle v-if="errors">
                            <v-alert
                                v-for="(error, index) in image_errors"
                                :key="index"
                                border="left"
                                close-text="Close Alert"
                                color="red lighten-2"
                                dark
                            >
                                {{ error[0] }}
                            </v-alert>
                        </v-card-subtitle>
                        <v-card-text>
                            <div class="pb-3 row mb-3">
                                <v-col
                                    cols="2"
                                    v-for="image in product.images"
                                    :key="image.id"
                                >
                                    <v-card>
                                        <v-img
                                            :src="
                                                `/storage/thumb/${image.file}`
                                            "
                                            class="white--text align-end"
                                            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                            height="120px"
                                        >
                                            <v-card-title>
                                                {{ image.size / 1000 }}
                                                KB</v-card-title
                                            >
                                        </v-img>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>

                                            <v-btn
                                                @click="deleteImage(image)"
                                                icon
                                                color="red"
                                            >
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </div>
                            <UploadImages
                                accept="image/png, image/gif, image/jpeg"
                                :max="6"
                                maxError="Max files exceed"
                                @changed="handleImages"
                                uploadMsg="upload product images"
                            />
                            <v-spacer></v-spacer>
                            <div class="text-right mt-5 mb-5">
                                <v-btn
                                    color="primary"
                                    @click="addNewImage"
                                    :loading="buttonLoading"
                                >
                                    <v-icon left dark>
                                        mdi-cloud-upload
                                    </v-icon>
                                    Update image
                                    <template v-slot:loader>
                                        <span>Loading...</span>
                                    </template>
                                </v-btn>
                            </div>
                        </v-card-text>
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
        <ConfirmationBox v-if="confirm" :confirm="confirm">
            <template v-slot:cancel>
                <v-btn small color="error" dark @click="cancel">
                    <v-icon left>mdi-cancel</v-icon>
                    no
                </v-btn>
            </template>
            <template v-slot:ok>
                <div>
                    <v-btn
                        small
                        color="green darken-1"
                        dark
                        @click="deleteItem"
                    >
                        <v-icon left>mdi-check-circle</v-icon>
                        yes
                    </v-btn>
                </div>
            </template>
        </ConfirmationBox>
        <v-dialog v-model="editAttributeDialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Edit Attribute</span>
                </v-card-title>
                <v-card-text>
                    <v-form
                        ref="attributes"
                        v-model="attribute_valid"
                        lazy-validation
                    >
                        <v-container>
                            <v-select
                                v-if="formData.has_color"
                                label="Color *"
                                :items="colors"
                                :item-text="'name'"
                                :item-value="'name'"
                                small-chips
                                outlined
                                dense
                                :rules="[required('Color')]"
                                v-model="single_attribute.color"
                            ></v-select>

                            <v-text-field
                                type="number"
                                label="Stock *"
                                :rules="[required('Stock')]"
                                v-model="single_attribute.quantity"
                                outlined
                                dense
                            ></v-text-field>
                            <v-text-field
                                label="SKU *"
                                v-model="single_attribute.sku"
                                :rules="[required('SKU')]"
                                outlined
                                dense
                            ></v-text-field>
                            <v-text-field
                                type="number"
                                label="Price *"
                                v-model="single_attribute.price"
                                :rules="[required('Price')]"
                                outlined
                                dense
                            ></v-text-field>
                            <v-combobox
                                v-if="formData.has_size"
                                v-model="single_attribute.sizes"
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
                        </v-container>
                    </v-form>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red"
                        dark
                        @click="editAttributeDialog = false"
                    >
                        <v-icon left>cancel</v-icon>
                        Close
                    </v-btn>
                    <v-btn
                        :loading="buttonLoading"
                        color="success"
                        @click="updateAttributes"
                        ><v-icon left>save</v-icon>
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import Editor from "@tinymce/tinymce-vue";
import UploadImages from "vue-upload-drop-images";
import ConfirmationBox from "../../components/ConfirmationBox.vue";
import { commonMixin } from "../../mixins/commonMixin";
import { ProductMixins } from "../../mixins/ProductMixins";
import { validation } from "../../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation, ProductMixins],
    components: {
        UploadImages,
        ConfirmationBox,
        Editor
    },
    data() {
        return {
            url: "",
            cover_error: [],
            editAttributeDialog: false,
            single_attribute: {},
            buttonLoading: false,
            optionButtonLoading: false,
            product: [],
            attribute_valid: true,
            option_valid: true,
            confirm: false,
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
            image_errors: [],

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
        confirmDialog(data) {
            this.single_attribute = data;
            this.confirm = true;
        },
        cancel() {
            this.confirm = false;
            this.single_attribute = {};
        },

        editAttribute(data) {
            this.single_attribute = {};
            this.single_attribute = data;
            this.editAttributeDialog = true;
        },
        addAttribute() {
            let product_id = this.$route.params.id;
            this.single_attribute = {
                product_id: product_id
            };
            this.editAttributeDialog = true;
            this.$refs.attributes.resetValidation();
        },
        deleteItem() {
            axios
                .delete(`/api/product/variant/${this.single_attribute.id}`)
                .then(res => {
                    this.$refs.attributes.resetValidation();
                    this.$toast.success(res.data.message, {
                        timeout: 5000
                    });

                    this.confirm = false;
                    this.single_attribute = {};

                    this.getProduct();
                })
                .catch(error => {
                    this.$toast.error(error.response.data.error, {
                        timeout: 2000
                    });
                });
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
                        this.getProduct();
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
        updateAttributes() {
            //this.$refs.attributes.resetValidation();

            if (this.$refs.attributes.validate()) {
                this.buttonLoading = true;

                axios
                    .post(`/api/product/variant`, this.single_attribute)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.editAttributeDialog = false;
                        //this.$router.push({ name: "Product" });
                        this.buttonLoading = false;
                        this.getProduct();
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
        updateProductOptions() {
            if (this.$refs.update_options.validate()) {
                this.optionButtonLoading = true;

                axios
                    .post(
                        `/api/update/product/options/${this.$route.params.id}`,
                        this.option_values
                    )
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.optionButtonLoading = false;
                        this.getProduct();
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;

                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
                        this.optionButtonLoading = false;
                    });
            }
        },
        getProduct() {
            axios
                .get(`/api/product/${this.$route.params.id}`)
                .then(result => {
                    this.product = result.data.data;
                    this.formData = this.product;
                    this.url = this.formData.cover;
                    this.meta_tags = this.product.meta_tags;
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
        },

        filePreview(e) {
            var file = e.target.files[0];
            this.url = URL.createObjectURL(file);
            var formData = new FormData();
            const config = {
                headers: {
                    "content-type": "multipart/form-data"
                }
            };
            formData.append("cover", file);
            this.buttonLoading = true;
            axios
                .post(
                    `/api/update/cover/${this.$route.params.id}`,
                    formData,
                    config
                )
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.cover_error = [];

                    // this.$router.push({ name: "Product" });
                    // this.image_errors = [];
                    this.buttonLoading = false;
                })
                .catch(error => {
                    this.cover_error = error.response.data.errors;

                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });

                    window.scrollTo(0, 0, { behavior: "smooth" });
                    this.buttonLoading = false;
                });
        },
        deleteImage(data) {
            axios
                .delete(`/api/product/image/${data.id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.getProduct();
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        addNewImage() {
            var formData = new FormData();
            const config = {
                headers: {
                    "content-type": "multipart/form-data"
                }
            };
            if (this.images) {
                this.images.forEach(image => {
                    formData.append("product_images[]", image, image.name);
                });
            }

            formData.append("id", this.$route.params.id);

            axios
                .post("/api/update/product/image", formData, config)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.images = [];
                    this.handleImages();
                    // this.$router.push({ name: "Product" });
                    this.image_errors = [];
                    this.getProduct();
                    this.buttonLoading = false;
                })
                .catch(error => {
                    this.image_errors = error.response.data.errors;

                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                    window.scrollTo(0, 0, { behavior: "smooth" });
                    this.buttonLoading = false;
                });
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
<style scoped>
.cover {
    display: none;
    visibility: none;
}
.cover-input {
    position: relative;
    text-align: center;
    border: 2px solid rgb(158, 135, 135);
    height: 150px;
    width: 150px;
    margin: auto;
    cursor: pointer;
    margin-top: 50px;
    background: rgb(172, 166, 166);
}
.cover-img {
    height: 150px;
    width: 150px;
    position: relative;
    object-fit: cover;
}
.icon {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
}
</style>
