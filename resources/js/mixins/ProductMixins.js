export const ProductMixins = {
    data() {
        return {
            colors: []
        };
    },
    methods: {
        getColors() {
            axios
                .get("/api/v1/colors")
                .then(res => {
                    this.colors = res.data;
                    console.log(this.colors);
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                });
        }
    },
    created() {
        this.getColors();
    },
    mounted() {}
};
