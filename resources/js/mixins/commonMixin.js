import axios from "axios";
import ValidationErrors from "../components/ValidationErrors.vue";
export const commonMixin = {
    components: {
        ValidationErrors
    },
    data() {
        return {
            formData: {},
            model: false,
            valid: true,
            errors: {},
            buttonLoading: false,
            options: {
                page: 25,
                itemsPerPage: 25,
                sortBy: ["parent_id"],
                sortDesc: [false],
                multiSort: false,
                mustSort: false
            }
        };
    },
    methods: {
        openDialog() {
            this.dialog = true;
        },
        closeModel() {
            this.dialog = false;
            this.formData = {};
            this.errors = {};
            this.$refs.form.reset();
        }
    }
};
