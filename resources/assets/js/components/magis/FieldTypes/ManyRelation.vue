<template>
    <div>
        <select :id="chosenId" :name="name + '[]'" class="form-control" :value="parsedValue" @input="input" multiple>
            <option v-for="i in items" :value="i.id" v-text="i[relationAttribute ? relationAttribute : 'name']" :selected="i.checked"></option>
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
                type: Array | String,
                default: function () {
                    return [];
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
                parsedValue: this.value
                    ? (
                        Array.isArray(this.value)
                            ? _.map(this.value, 'id')
                            : _.map(JSON.parse(this.value), 'id')
                    )
                    : [],
                error: Array.isArray(this.initialError)
                    ? this.initialError
                    : JSON.parse(this.initialError),
            };
        },

        mounted() {
            this.$http.get(url(this.name + '?forceLight=true')).then((response) => {
                this.items = response.body.items.map((val) => {
                    val.checked = _.indexOf(this.parsedValue, val.id) >= 0;
                    return val;
                });

                this.$nextTick(() => {
                    $('#' + this.chosenId).chosen({ width: '100%' }).change(() => {
                        this.input();
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

                if (newVal) {
                    var parsedNewVal = [];
                    if (!Array.isArray(newVal)) {
                        parsedNewVal = JSON.parse(newVal);
                    } else {
                        parsedNewVal = newVal;
                    }

                    parsedNewVal.map((val) => {
                        var index = _.findIndex(this.items, { id: val.id });
                        var item = this.items[index];
                        item.checked = true;
                        this.items.splice(index, 1, item);
                    });
                }

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
            input(event) {
                this.$emit('input', this.getValues());
            },

            getValues() {
                return $('#' + this.chosenId).val().map(
                    val => _.find(this.items, { id: parseInt(val) })
                );
            },

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