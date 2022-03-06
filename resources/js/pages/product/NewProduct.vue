<template>
    <div></div>
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
