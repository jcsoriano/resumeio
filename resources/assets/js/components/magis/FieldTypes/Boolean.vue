<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <div>
        <label :for="name">
            <input 
                type="checkbox" 
                :id="name" 
                :name="name"
                :placeholder="title" 
                value="1"
                @change="input"
                :disabled="disabled"
                :checked="value == '1'"
            >
            <b v-text="label"></b>
        </label>
        <div v-show="error" class="error" v-for="e in parsedError" v-text="e"></div>
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
                default: this.name,
            },
            value: {
                type: String | Number,
                default: '',
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            disabled: {
                type: Boolean,
                default: false,
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
        
        watch: {
            error(newVal) {
                this.parsedError = this.error;
            },
        },
        
        methods: {
            input(event) {
                this.$emit('input', event.target.checked);
            },
        },
    };
</script>
