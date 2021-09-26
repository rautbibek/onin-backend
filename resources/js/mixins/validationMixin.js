export const validation = {
    data() {
        return {
            select(propertyType) {
                return v => !!v || `${propertyType} field is mandatory.`;
            },
            checked(propertyType) {
                return v => !!v || `${propertyType} field is mandatory.`;
            },

            required(propertyType) {
                return v =>
                    (v && v.length > 0) ||
                    `${propertyType} field is mendatory. `;
            },

            // priceVlidate(propertyType) {
            //     return v =>
            //         (v && v > 99) ||
            //         `${propertyType} must be grater than or equal to 100.`;
            // },

            minLength(propertyType, length) {
                return v =>
                    (v && v.length >= length) ||
                    `${propertyType} must be at least ${length} characters`;
            },

            maxLength(propertyType, length) {
                return v =>
                    (v && v.length <= length) ||
                    `${propertyType} must be less than ${length} characters`;
            },
            email(propertyType) {
                return v =>
                    /.+@.+\..+/.test(v) ||
                    `${propertyType} must be valid email`;
            }
        };
    }
};
