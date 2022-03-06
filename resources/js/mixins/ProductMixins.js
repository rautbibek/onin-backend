import axios from "axios";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
export const ProductMixins = {
    components: {
        Treeselect
    },
    data() {
        return {
            colors: [],
            collections: [],
            product_collection: [],
            meta_tags: [],
            selected_category: {},
            product_tags: [],
            category_options: [],
            categories: [],

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

        checkColorAndSizeIfAvailable() {
            this.formData.category_id = this.selected_category.id;
            this.formData.has_color = this.selected_category.has_color;
            this.formData.has_size = this.selected_category.has_size;
            this.getOptions();
        },
        getOptions() {
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
                .get("/api/select/category")
                .then(res => {
                    this.categories = res.data.data;
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
