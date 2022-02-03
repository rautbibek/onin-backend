import axios from "axios";

export const ProductMixins = {
    data() {
        return {
            colors: [],
            collections: [],
            product_collection: [],
            meta_tags: [],
            product_tags: [],
            category_options: [],
            categories: [],
            subcategories: [],
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
        getColors() {
            axios
                .get("/api/v1/colors")
                .then(res => {
                    this.colors = res.data;
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
        },
        getCollection() {
            axios
                .get("/api/v1/collection")
                .then(res => {
                    this.collections = res.data;
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
        },
        getSubcategory() {
            this.categories.find(category => {
                if (category.id === this.formData.parent_id) {
                    this.subcategories = category.children;
                }
            });
        },
        checkColorAndSizeIfAvailable() {
            this.subcategories.find(scat => {
                if (scat.id === this.formData.category_id) {
                    this.formData.has_color = scat.has_color;
                    this.formData.has_size = scat.has_size;
                }
            });
        },
        getOptions() {
            this.checkColorAndSizeIfAvailable();
            axios
                .get(`/api/v1/category/options/${this.formData.category_id}`)
                .then(res => {
                    this.category_options = res.data;
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
            this.getBrand();
        },
        getBrand() {
            axios
                .get(`/api/v1/category/brand/${this.formData.category_id}`)
                .then(res => {
                    this.brands = res.data;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },
        getCategory() {
            axios
                .get("/api/category")
                .then(res => {
                    this.categories = res.data;
                })
                .catch(error => {
                    console.log(error);
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
    created() {
        this.getColors();
        this.getCollection();
        this.getCategory();
    },

    mounted() {
        // /this.getBrand();
    }
};
