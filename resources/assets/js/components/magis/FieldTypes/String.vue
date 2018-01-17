<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <div>
        <a v-if="changePasswordLink && resource && disabled && type == 'password'"
            :href="resource + '/change-password' + (slug ? '/' + slug : '')"
            class="btn btn-default btn-block"
        >Change Password</a>
        <input
            v-if="!changePasswordLink || !resource || !disabled || type != 'password'"
            :type="type == 'string' ? 'text' : type" 
            :class="inputClass" 
            :id="id" 
            :name="name"
            :placeholder="title" 
            :value="value"
            v-validate="rules"
            @input="input"
            @focus="focus"
            @blur="emit('blur')"
            @keyup.enter="emit('enter')"
            :disabled="disabled"
        >
        <div v-show="error" class="error" v-for="e in parsedError" v-text="e"></div>
        <div v-show="validationErrors.has(name)" class="error" v-text="validationErrors.first(name)"></span>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: 'string',
            },
            resource: {
                type: String,
                default: '',
            },
            slug: {
                type: String,
                default: '',
            },
            changePasswordLink: {
                type: Boolean,
                default: false,
            },
            value: {
                type: String,
                default: '',
            },
            rules: {
                type: String,
                default: '',
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            type: {
                type: String,
                default: 'string',
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            inputClass: {
                type: String,
                default: 'form-control',
            },
            label: {
                type: String,
                default: '',
            },
            pattern: {
                type: String,
                default: '*',
            },
            selectAllOnFocus: {
                type: Boolean,
                default: false,
            },
            idModifier: {
                type: String,
                default() {
                    return Math.floor(Math.random() * 1000).toString();
                },
            }
        },

        computed: {
            title() {
                return this.label ? this.label : _.startCase(this.name);
            },

            id() {
                return this.name + '-' + this.idModifier;
            },
        },

        data() {
            return {
                parsedError: Array.isArray(this.error) ? this.error : JSON.parse(this.error),
                // validationErrors: this.$validator.errorBag,
            };
        },

        watch: {
            error(newVal) {
                this.parsedError = this.error;
            },
        },

        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },

            url(path) {
                return url(path);
            },

            emit(event) {
                this.$emit(event);
            },

            focus() {
                this.$emit('focus');

                if (this.selectAllOnFocus) {
                    document.getElementById(this.id).select();
                }
            },
        },
    };
</script>
