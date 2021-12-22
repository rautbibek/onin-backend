import axios from "axios";

export const ProductMixins = {
    data() {
        return {
            colors: [],
            collections: []
        };
    },
    methods: {
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
                    console.log(this.collections);
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
        }
    },
    created() {
        this.getColors();
        this.getCollection();
    },
    mounted() {}
};
