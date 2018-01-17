<template>
    <div v-html="item"></div>
</template>

<script>
    export default {
        props: {
            item: {
                type: String,
                required: true
            },
        }
    }
</script>
