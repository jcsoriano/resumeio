<style scoped>
    .list-group-item:hover .btn-group {
        display:inline;
    }
    .list-group-item .btn-group {
        display:none;
    }
</style>

<template>
    <ul :class="'list-group ' + (heavy ? '': 'slim-scroll')">
        <li class="list-group-item" v-for="item in items">
            <component :item="item" :is="type" :resource="resource" :permissions="permissions" :buttons="buttons"></component>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                required: true
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
                default: 'default'
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
