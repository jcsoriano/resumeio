<style scoped>
    .error {
        color:red;
    }
</style>
<template>
    <div>
        <textarea 
            :class="inputClass" 
            :id="name" 
            :name="name"
            :placeholder="title" 
            :value="value"
            :rows="inputRows"
            @input="input"
        ></textarea>
        <span v-if="parsedError" class="error" v-for="e in parsedError" v-text="e"></span>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: 'text',
            },
            value: {
                type: String,
                default: ''
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                }
            },
            inputClass: {
                type: String,
                default: 'form-control'
            },
            inputRows: {
                type: String,
                default: '3'
            },
        },
        computed: {
            title() {
                return _.startCase(this.name);
            }
        },
        watch: {
            error(newVal) {
                this.parseError();
            }
        },
        mounted() {
            this.parseError();
        },
        data() {
            return {
                parsedError : []
            }
        },
        methods: {
            parseError() {
                this.parsedError = Array.isArray(this.error) ? this.error : JSON.parse(this.error)
            },
            input(event) {
                this.$emit('input', event.target.value);
            }
        }
    }
</script>
