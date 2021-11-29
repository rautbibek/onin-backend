import axios from "axios";

const state = {
    brands: [],
    loading: false
};
const getters = {
    allBrands: state => state.brands
};
const actions = {
    async fetchBrands({ commit }) {
        const response = await axios.get("/api/brand");
        commit("setBrand", response.data);
    }
};
const mutations = {
    setBrand: (state, brands) => (state.brands = brands)
};
export default {
    state,
    getters,
    actions,
    mutations
};
