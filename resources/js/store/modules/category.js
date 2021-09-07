const state = {
    categories: [
        {
            name: "Frozen Yogurt",
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0
        },
        {
            name: "Ice cream sandwich",
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3
        },
        {
            name: "Eclair",
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0
        }
    ]
};
const getters = {
    allCategories: state => state.categories
};
const actions = {};
const mutations = {};

export default {
    state,
    getters,
    actions,
    mutations
};
