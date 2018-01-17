<style scoped>
    .error {
        color:red;
    }
    .big-star {
        cursor: pointer;
    }
</style>
<template>
    <div>
        <div>
            <v-star-rating 
                :show-rating="false" 
                :rating="value" 
                :read-only="readOnly" 
                :increment="increment" 
                :star-size="starSize"
                :max-rating="maxRating"
                @rating-selected="input"
            ></v-star-rating>
        </div>
        <input type="hidden" :name="name" :value="value" />
        <span v-if="parsedError" class="error" v-for="e in parsedError" v-text="e"></span>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
            },
            value: {
                type: Number | String,
                default: '',
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            numStars: {
                type: Number,
                default: 5,
            },
            increment: {
                type: Number,
                default: 1,
            },
            readOnly: {
                type: Boolean,
                default: false,
            },
            starSize: {
                type: Number,
                default: 40,
            },
            maxRating: {
                type: Number,
                default: 5,
            },
        },

        computed: {
            title() {
                return _.startCase(this.name);
            },
        },

        watch: {
            error(newVal) {
                this.parseError();
            },
        },

        mounted() {
            this.parseError();
        },

        data() {
            return {
                parsedError : [],
            };
        },

        methods: {
            parseError() {
                this.parsedError = Array.isArray(this.error) ? this.error : JSON.parse(this.error);
            },

            input(value) {
                if (!this.readOnly) {
                    this.$emit('input', value);
                }
            },
        },
    };
</script>
