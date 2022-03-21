<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card elevation="2" tile>
            <v-card-title>Aoo Logo</v-card-title>
            <hr />
            <v-card-text>
                <v-row>
                    <v-col class="text-center">
                        <div
                            style="display:flex; justify-content:spance-between; aligne-item:center"
                            class="ma-3"
                        >
                            <v-img :src="url" height="120px"></v-img>
                        </div>
                        <v-file-input
                            @change="changeLogo"
                            label="App Logo"
                            outlined
                            prepend-icon="mdi-camera"
                        ></v-file-input>
                    </v-col>
                    <v-col class="text-right"> </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            url: "",
            breadcrumb: [
                {
                    text: "Dashboard",
                    disabled: false,
                    to: "/dashboard"
                },
                {
                    text: "Setting",
                    disabled: true,
                    to: "/setting"
                }
            ]
        };
    },
    methods: {
        changeLogo(e) {
            this.url = "";

            if (e) {
                this.url = URL.createObjectURL(e);
            }
        }
    },
    created() {
        // axios
        //     .get("api/v1/product/2")
        //     .then(res => {
        //         console.log(res.data);
        //     })
        //     .catch();
    }
};
</script>

<style lang="scss" scoped></style>
