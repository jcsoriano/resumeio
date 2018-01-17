<template>
    <div>
        <select :id="chosenId" :name="name" class="form-control" :value="value">
            <option v-for="i in items" :value="i.id" v-text="i[relationAttribute ? relationAttribute : 'name']" :selected="i.id == value"></option>
        </select>
        <div v-show="error" class="error" v-for="e in error" v-text="e"></div>
    </div>
</template>

<script>
    export default {
        props: {
            idModifier: {
                type: String,
                default: '',
            },
            name: {
                type: String,
                required: true,
            },
            relationAttribute: {
                type: String,
                default: 'name',
            },
            value: {
                type: String | Number,
                default: function () {
                    return 1;
                },
            },
            initialError: {
                type: Array,
                default: function () {
                    return [];
                },
            },
        },

        data() {
            return {
                items: [],
                origKeys: [],
                error: Array.isArray(this.initialError)
                    ? this.initialError
                    : JSON.parse(this.initialError),
            };
        },

        mounted() {
            this.$http.get(url(_.pluralize(this.name) + '?forceLight=true')).then((response) => {
                this.items = response.body.items.map((val) => {
                    val.checked = _.indexOf(this.parsedValue, val.id) >= 0;
                    return val;
                });

                this.$nextTick(() => {
                    $('#' + this.chosenId).chosen({ width: '100%' }).change((val) => {
                        this.$emit('input', $('#' + this.chosenId).val());
                    });
                });
            }, (error) => {
                this.growlError(error);
            });
        },

        watch: {
            value(newVal) {
                this.items = this.items.map((val) => {
                    val.checked = false;
                    return val;
                });

                this.$nextTick(() => {
                    $('#' + this.chosenId).trigger('chosen:updated');
                });
            },
        },

        computed: {
            title() {
                return _.titleize(this.name);
            },

            chosenId() {
                return 'chosen-' + this.name + '-' + this.idModifier;
            },
        },

        methods: {
            growlError(error) {
                $.growl.error({
                    title: 'Error',
                    message: 'Something went wrong. Please check your internet connection and reload the page.',
                });
                console.log(error);
            },
        },
    };
</script>