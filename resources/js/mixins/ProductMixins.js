import axios from "axios";
import { mapGetters, mapActions } from "vuex";
export const ProductMixins = {
    data() {
        return {
            loading_options: false,
            colors: [],
            collections: [],
            product_collection: [],
            meta_tags: [],
            selected_category: {},
            product_tags: [],
            category_options: [],
            categories: [],
            items: ["Basic Information", "Product Attributes", "Images", "SEO"],
            buttonLoading: false,
            brands: [],
            sizes: [],
            errors: [],
            available_colors: [],
            product_colors: [],
            discount_type: [
                {
                    name: "Flat Rate(Rs)",
                    value: "flat"
                },
                {
                    name: "Percentage(%)",
                    value: "percent"
                }
            ],
            product_attribute_values: [
                {
                    color: "",
                    sku: "",
                    price: null,
                    sizes: []
                }
            ],
            color_values: [],
            option_values: {}
        };
    },
    methods: {
        handleImages(files) {
            this.images = files;
        },
        ...mapActions(["getCategories"]),
        getColors() {
            axios
                .get("/api/v1/colors")
                .then(res => {
                    this.colors = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.errors, {
                        timeout: 2000
                    });
                });
        },

        getCollection() {
            axios
                .get("/api/v1/collection")
                .then(res => {
                    this.collections = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.errors, {
                        timeout: 2000
                    });
                });
        },

        checkColorAndSizeIfAvailable() {
            this.loading_options = true;
            axios
                .get(`/api/single/category/${this.formData.category_id}`)
                .then(res => {
                    this.formData.has_color = res.data.has_color;
                    this.formData.has_size = false;
                    this.brands = res.data.brand;
                    this.category_options = res.data.options;
                    if (this.category_options.length > 0) {
                        this.items = [
                            "Basic Information",
                            "Product Attributes",
                            "Product Options",
                            "Images",
                            "SEO"
                        ];
                    } else {
                        this.items = [
                            "Basic Information",
                            "Product Attributes",

                            "Images",
                            "SEO"
                        ];
                    }
                    this.loading_options = false;
                })
                .catch(error => {
                    this.loading_options = false;
                });
        },

        getSingleProductType() {
            this.product_types.find(product_type => {
                if (product_type.id === this.product_type_id) {
                    this.product_type = [];
                    this.product_type.push({ ...product_type.field });
                    product_type.field.map(function(value, key) {
                        key = value.name;
                    });
                }
            });
        },
        totalAttributes() {
            this.calculate_color_values();
            if (this.available_colors.length > 0) {
                this.product_attribute_values = [];
                this.available_colors.forEach(color => {
                    this.product_attribute_values.push({
                        color: color,
                        sku: "",
                        price: null,
                        sizes: []
                    });
                });
                return this.available_colors.length;
            }
            return 0;
        },
        calculate_color_values() {
            this.color_values = [];
            this.available_colors.forEach(color => {
                this.colors.find(single_color => {
                    if (single_color.name == color) {
                        this.color_values.push(single_color);
                    }
                });
            });
        },
        removeAttributes(index) {
            this.product_attribute_values.splice(index, 1);
        }
    },
    computed: mapGetters(["fetAllCategories"]),
    created() {
        this.getColors();
        this.getCollection();
        this.getCategories();
    },

    mounted() {
        // /this.getBrand();
    }
};
