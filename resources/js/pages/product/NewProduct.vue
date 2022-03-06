<template>
    <div>
        <b-link class="mb-3" :items="items"></b-link>
        <div class="ma-3">
            <v-form ref="form" value v-model="valid" lazy-validation>
                <v-card flat>
                    <v-card-title>Basic Information</v-card-title>
                    <v-card-subtitle v-if="errors">
                        <v-alert
                            v-for="(error, index) in errors"
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
                        <v-row class="pa-1">
                            <v-col cols="12">
                                <!-- @change="getSubcategory" -->
                                {{ selected_category.length }}
                                <v-autocomplete
                                    v-model="selected_category"
                                    :items="categories"
                                    item-text="name"
                                    return-object
                                    label="Choose Category *"
                                    @change="checkColorAndSizeIfAvailable"
                                    :rules="[
                                        v =>
                                            (v && v.id) ||
                                            'Category field is mandatory.'
                                    ]"
                                    outlined
                                    dense
                                >
                                    <template v-slot:selection="data">
                                        <v-chip
                                            v-bind="data.attrs"
                                            :input-value="data.selected"
                                            @click="data.select"
                                        >
                                            <span v-if="data.item.parent"
                                                ><span
                                                    v-if="
                                                        data.item.parent.parent
                                                    "
                                                    >{{
                                                        data.item.parent.parent
                                                            .name
                                                    }}
                                                    -></span
                                                >{{
                                                    data.item.parent.name
                                                }}
                                                -></span
                                            >{{ data.item.name }}
                                        </v-chip>
                                    </template>
                                    <template v-slot:item="data">
                                        <template>
                                            <v-list-item-content>
                                                <v-list-item-title
                                                    ><span
                                                        v-if="data.item.parent"
                                                        ><span
                                                            v-if="
                                                                data.item.parent
                                                                    .parent
                                                            "
                                                            >{{
                                                                data.item.parent
                                                                    .parent.name
                                                            }}
                                                            -></span
                                                        >{{
                                                            data.item.parent
                                                                .name
                                                        }}
                                                        -></span
                                                    >
                                                    {{
                                                        data.item.name
                                                    }}</v-list-item-title
                                                >
                                                <v-list-item-subtitle></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col cols="12" md="6" lg="6" sm="12" xs="12">
                                <v-text-field
                                    v-model="formData.title"
                                    label="Product Name *"
                                    outlined
                                    placeholder="Product Name Eg: Apple MacBook Pro13 M1 Chip with 8-Core CPU"
                                    dense
                                    :rules="[
                                        required('Product Title'),
                                        maxLength('Product Title', 200)
                                    ]"
                                    counter="200"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="6" lg="6" sm="12" xs="12">
                                <v-text-field
                                    v-model="formData.search_text"
                                    label="Search Text *"
                                    placeholder="Best laptop under 100,000 price"
                                    outlined
                                    dense
                                    :rules="[
                                        required('Search Text'),
                                        maxLength('Search Text', 200)
                                    ]"
                                    counter="200"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="4" lg="4" sm="6" xs="12">
                                <v-autocomplete
                                    v-model="formData.brand_id"
                                    :items="brands"
                                    :item-text="'name'"
                                    :item-value="'id'"
                                    label="Brand"
                                    outlined
                                    dense
                                ></v-autocomplete>
                            </v-col>

                            <v-col cols="12" md="4" lg="4" sm="6" xs="12">
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
                            </v-col>
                            <v-col cols="12" md="4" lg="4" sm="6" xs="12">
                                <v-row>
                                    <v-col>
                                        <v-select
                                            :items="discount_type"
                                            item-text="name"
                                            item-value="value"
                                            v-model="formData.discount_value"
                                            outlined
                                            dense
                                            clearable
                                            label="Discount Type"
                                        ></v-select>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            label="Discount"
                                            type="number"
                                            outlined
                                            dense
                                            clearable
                                            v-model="formData.discount"
                                            placeholder="Discount"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-row>
                                <v-col
                                    cols="12"
                                    style="text-aligne:right"
                                    class="ml-3"
                                >
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
                                </v-col>
                            </v-row>
                        </v-row>
                    </v-card-text>
                </v-card>

                <v-card class="mt-3" flat>
                    <v-card-title>Product Attributes</v-card-title>
                    <v-card-text>
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
                                    :label="cat_opts.code + ' *'"
                                >
                                </v-autocomplete>
                            </v-col>
                        </div>

                        <v-row>
                            <v-col cols="6" v-if="formData.has_color">
                                <v-autocomplete
                                    v-model="available_colors"
                                    outlined
                                    x-small-chips
                                    :items="colors"
                                    :item-text="'name'"
                                    :item-value="'name'"
                                    :rules="[combo('Available Colors')]"
                                    Basic
                                    dense
                                    multiple
                                    @change="totalAttributes"
                                    label="Available Colors *"
                                >
                                    <template v-slot:item="{ item }">
                                        {{ item.name }}
                                    </template>
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
                                                removeColor(item.code)
                                            "
                                        >
                                            <v-avatar
                                                class="white--text"
                                                :style="{
                                                    background: item.code
                                                }"
                                                left
                                                :color="item.code"
                                                v-text="
                                                    item.name
                                                        .slice(0, 1)
                                                        .toUpperCase()
                                                "
                                            ></v-avatar>
                                            <strong>{{ item.name }}</strong
                                            >&nbsp;
                                        </v-chip>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                        </v-row>

                        <div
                            v-for="(attr, index) in product_attribute_values"
                            :key="index"
                            style="border:1px solid black; padding:10px; margin-top:10px; margin-bottom:10px; border-radius:10px; position:relative"
                        >
                            <v-row class="mt-2">
                                <v-col v-if="available_colors.length > 0">
                                    <v-text-field
                                        v-model="attr.color"
                                        label="color"
                                        outlined
                                        dense
                                        disabled
                                        :rules="[
                                            required('Color'),
                                            maxLength('color', 100)
                                        ]"
                                    ></v-text-field>
                                </v-col>
                                <v-col v-if="formData.has_size">
                                    <v-combobox
                                        v-model="attr.sizes"
                                        label="Available Sizes *"
                                        x-small-chips
                                        close
                                        multiple
                                        :rules="[combo('Sizes')]"
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
                                                close
                                                @click="select"
                                                @click:close="removeSizes(item)"
                                            >
                                                <strong>{{ item }}</strong
                                                >&nbsp;
                                            </v-chip>
                                        </template>
                                    </v-combobox>
                                </v-col>

                                <v-col>
                                    <v-text-field
                                        v-model="attr.stock"
                                        type="number"
                                        label="Stock *"
                                        outlined
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
                                        label="SKU *"
                                        outlined
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
                                        label="Price *"
                                        outlined
                                        dense
                                        :rules="[
                                            required('Price'),
                                            maxLength('Price', 200)
                                        ]"
                                    ></v-text-field>
                                </v-col>
                            </v-row>

                            <v-btn
                                v-if="product_attribute_values.length > 1"
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
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>

                <v-card flat class="mt-3">
                    <v-card-title>Detailed Description</v-card-title>
                    <v-card-text>
                        <v-col cols="12">
                            <span style="font-weight:bold; margin-bottom:10px"
                                >Short description :</span
                            >
                            <editor
                                api-key="mw953fmxqhyj06dim9f021ezz1q7vc9klgm46zhj4lahay02"
                                v-model="formData.short_description"
                                aria-placeholder="short description"
                                aria-required="Requrie"
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
                        </v-col>
                        <v-col cols="12">
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
                        </v-col>
                    </v-card-text>
                </v-card>

                <v-card flat class="mt-3">
                    <v-card-title>Product Image</v-card-title>
                    <v-card-text>
                        <v-col class="text-center">
                            <img
                                v-if="url"
                                :src="url"
                                height="100px"
                                width="100px"
                                alt="logo"
                            />
                        </v-col>
                        <v-row class="pa-3">
                            <v-col>
                                <v-file-input
                                    v-model="formData.cover"
                                    color="deep-purple accent-4"
                                    counter
                                    @change="filePreview"
                                    label="Cover Image"
                                    :rules="[required('cover')]"
                                    placeholder="Select your files"
                                    append-icon="mdi-camera"
                                    outlined
                                    hide-spin-buttons
                                    prepend-icon
                                    show-size
                                    accept="image/jpg, image/png, image/png, image/webp "
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
                        <UploadImages @changed="handleImages"
                    /></v-card-text>
                </v-card>

                <v-card flat class="mt-3">
                    <v-card-title
                        >SEO (Meta title keyword & description)</v-card-title
                    >

                    <v-card-text>
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
                                            @click:close="removeMetaTags(item)"
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
                    <v-card-actions class="mb-5 mt-5">
                        <v-spacer></v-spacer>
                        <v-btn color="error">
                            <v-icon right dark>
                                mdi-cancel
                            </v-icon>
                            CANCEL
                        </v-btn>
                        <v-btn color="primary" @click="saveProduct">
                            <v-icon right dark>
                                mdi-cloud-upload
                            </v-icon>
                            SAVE
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </div>
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
            e6: 1,
            url: "",
            images: [],
            items: [
                {
                    text: "Dashboard",
                    disabled: false,
                    href: "/dashboard"
                },
                {
                    text: "Product",
                    disabled: false,
                    href: "/Product"
                },
                {
                    text: "Add Product",
                    disabled: true,
                    href: "/Product"
                }
            ],
            formData: {},
            errors: [],

            product_status: true,

            cat: ""
        };
    },

    methods: {
        removeMetaTags(item) {
            this.meta_tags.splice(this.meta_tags.indexOf(item), 1);
        },
        removeColor(item) {
            this.available_colors.splice(
                this.available_colors.indexOf(item),
                1
            );
            this.totalAttributes();
        },
        removeSizes(item) {
            this.sizes.splice(this.sizes.indexOf(item), 1);
            this.totalAttributes();
        },

        removerProductTags(item) {
            this.product_tags.splice(this.product_tags.indexOf(item), 1);
        },

        filePreview(e) {
            this.url = URL.createObjectURL(e);
        },

        saveProduct() {
            if (this.$refs.form.validate()) {
                var formData = new FormData();
                const config = {
                    headers: {
                        "content-type": "multipart/form-data"
                    }
                };
                _.each(this.formData, (value, key) => {
                    formData.append(key, value);
                });

                this.images.forEach(image => {
                    formData.append("product_images[]", image, image.name);
                });
                formData.append("status", this.product_status);
                this.product_tags.forEach(product_tag => {
                    formData.append("product_tags[]", product_tag);
                });
                // this.product_attribute_values.forEach(product_attribute => {
                //     formData.append("product_attribute[]", product_attribute);
                // });
                this.meta_tags.forEach(meta_tag => {
                    formData.append("meta_tags[]", meta_tag);
                });
                this.sizes.forEach(size => {
                    formData.append("sizes[]", size);
                });

                this.product_collection.forEach(collection => {
                    formData.append("product_collection[]", collection);
                });

                // product_collection

                formData.append(
                    "option_values",
                    JSON.stringify(this.option_values)
                );
                formData.append(
                    "product_atributes",
                    JSON.stringify(this.product_attribute_values)
                );

                axios
                    .post("/api/product", formData, config)
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
                        window.scrollTo(0, 0, { behavior: "smooth" });
                        this.buttonLoading = false;
                    });
            } else {
                window.scrollTo(0, 0, { behavior: "smooth" });
                this.$toast.error(
                    "Somethign weng wrong please recheck your form ",
                    {
                        timeout: 2000
                    }
                );
            }
        },

        addItem() {
            this.product_types.find(product_type => {
                if (product_type.id === this.product_type_id) {
                    this.product_type.push({ ...product_type.field });
                    this.product_attribute_values.push({});
                }
            });
        },
        removeItem(index) {
            this.product_type.splice(index, 1);
        }
    }
};
</script>
