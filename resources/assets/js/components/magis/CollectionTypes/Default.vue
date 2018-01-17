<template>
    <div>
        <component v-for="item in items" :item="item" :is="type" :resource="resource" :permissions="permissions" :buttons="buttons"></component>
    </div>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                required: true,
            },
            resource: {
                type: String,
                required: true,
            },
            permissions: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            heavy: {
                type: Boolean,
                default: false,
            },
            type: {
                type: String,
                default: 'default',
            },
            buttons: {
                type: Array,
                default: function () {
                    return [];
                },
            },
        },
    };
</script>
