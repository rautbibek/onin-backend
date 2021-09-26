<template>
    <div v-if="errors">
        <v-alert
            v-for="(error, index) in errors"
            :key="index"
            dense
            type="error"
            color="error"
            dismissible
        >
            {{ error[0] }}
        </v-alert>
    </div>
</template>
<script>
export default {
    name: "ValidationErrors",
    props: {
        errors: {
            Type: Object
        }
    }
};
</script>
