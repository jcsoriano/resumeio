<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <div :class="'form-group' + (Array.isArray(this.parsedError) && parsedError.length > 0 ? ' has-error' : '')">
        <label :for="name" :class="labelClass + ' control-label'" v-text="type != 'boolean' ? (label ? label : title) : ''"></label>
        <div :class="inputWrapperClass">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true,
            },
            label: {
                type: String,
                default: this.title,
            },
            type: {
                type: String,
                required: false,
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            labelClass: {
                type: String,
                default: 'col-sm-2',
            },
            inputWrapperClass: {
                type: String,
                default: 'col-sm-10',
            },
        },

        computed: {
            title() {
                return _.titleize(this.name);
            },
        },

        data() {
            return {
                parsedError: Array.isArray(this.error) ? this.error : JSON.parse(this.error),
            };
        },
    };
</script>
