import axios from "axios";

export const addressMixin = {
    data() {
        return {
            state: [],
            discrict: [],
            city_under_district: [],
            district_under_state: [],
            city: [],
            local_area: []
        };
    },
    methods: {
        getAllStates() {
            axios
                .get("/api/v1/all/state")
                .then(res => {
                    this.state = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        getAllDistrict() {
            axios
                .get("/api/v1/all/district")
                .then(res => {
                    this.discrict = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        getAllCity() {
            axios
                .get("/api/v1/all/cities")
                .then(res => {
                    this.city = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        getDistrictByState(state_id) {
            axios
                .get(`/api/v1/district/${state_id}`)
                .then(res => {
                    this.district_under_state = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        getCityByDistrict(district_id) {
            axios
                .get(`/api/v1/city/${district_id}`)
                .then(res => {
                    this.district_under_state = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },
        getLocalAreaByCity(city_id) {
            axios
                .get(`/api/v1/local/area/${city_id}`)
                .then(res => {
                    this.district_under_state = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        }
    }
};
