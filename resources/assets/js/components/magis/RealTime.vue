<template>
    <component 
        :is="'magis-' + (typeMap[type] ? typeMap[type] : type)"
        :id-modifier="'real-time-' + value.id"
        :relation-attribute="relationAttributes[name]"
        :resource="resource"
        :type="type"
        :slug="value.slug"
        v-model="value[name]"
        @input="input"
        :name="name"
        :path="path"
        label=""
        :aspect-ratio="type == 'photo' && value[name + '_aspect_ratio'] ? value[name + '_aspect_ratio'] : false"
        timeout="200"
    >
    </component>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: true,
            },
            value: {
                default: function () {
                    return {};
                },
            },
            typeMap: {
                type: Object,
                required: function () {
                    return {};
                },
            },
            path: {
                type: String,
            },
            type: {
                type: String,
                default: 'string',
            },
            relationAttributes: {
                type: Object | Array,
                default: function () {
                    return {};
                },
            },
            debounceTime: {
                type: String | Number,
                default: 1500,
            },
        },

        data() {
            return {
                debouncedInput: _.debounce((val) => {
                    console.log(val);
                    let values = {};
                    values[this.name] = this.value[this.name];
                    values.onlyKey = this.name;

                    this.$http.put(url(this.url), values).then((response) => {
                        console.log(response.body);
                        if (response.body.status == 'success') {
                            let whole = this.value;
                            whole[this.name] = val;
                            this.$emit('input', whole);
                            this.growlNotice('Changes have been saved');
                        }
                    }, (error) => {
                        App.error(error);
                    });
                }, this.debounceTime),
            };
        },

        computed: {
            url() {
                return this.resource + '/' + this.value.slug;
            },
        },

        methods: {
            input(val) {
                this.debouncedInput(val);
            },

            growlNotice(message) {
                $.growl.notice({
                    title: 'Success',
                    message: message,
                });
            },
        },
    };
</script>

