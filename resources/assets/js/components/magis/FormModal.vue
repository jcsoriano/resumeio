<template>
    <modal :id="'modal-' + resource" :show="show" @close="close">
        <h4 slot="modal-title" class="modal-title" v-text="action + ' ' + title"></h4>
        <div slot="modal-body" class="modal-body">
            <div v-if="Object.keys(typeMap).length > 0 && type != 'mediumText'" v-for="(type, key) in form" class="row">
                <form-horizontal :label="label(key)" :name="key" :type="type">
                    <component 
                        :is="'magis-' + (typeMap[type] ? typeMap[type] : type)"
                        id-modifier="form-modal"
                        :relation-attribute="relationAttributes[key]"
                        :resource="resource"
                        :type="type"
                        :slug="values['slug']"
                        v-model="values[key]"
                        :error="errors[key]"
                        :name="key"
                        :path="path"
                        :label="label(key)"
                        :aspect-ratio="type == 'photo' && values[key + '_aspect_ratio'] ? values[key + '_aspect_ratio'] : false"
                        timeout="400"
                        :disabled="_.indexOf(disabledKeys, key) >= 0 || key == 'is_draft' && !hasPermission('draft') || key == 'published_at' && !hasPermission('schedule')"
                    >
                    </component>
                </form-horizontal>
            </div>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default" @click="close">Close</button>
            <button type="button" class="btn btn-primary" @click="save" >Save</button>
        </div>
    </modal>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                required: true,
            },
            typeMap: {
                type: Object,
                required: true,
            },
            currentUser: {
                type: Object,
                required: true,
            },
            permissions: {
                type: Object,
                required: true,
            },
            form: {
                type: Object,
                required: true,
            },
            relationAttributes: {
                type: Object | Array,
                default: {},
            },
            values: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            fieldLabels: {
                type: Object | Array,
                default: function () {
                    return {};
                },
            },
            action: {
                type: String,
                default: 'Create New',
            },
            show: {
                type: Boolean,
                default: false,
            },
            errors: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            disabledKeys: {
                type: Array,
                default: function () {
                    return [];
                },
            },
        },

        computed: {
            title() {
                return _.singularize(_.startCase(this.resource));
            },

            path() {
                return this.resource + '/' + (this.values.id ? this.values.id : 'new');
            },

            singular() {
                return _.singularize(this.resource);
            },
        },

        data() {
            return {
                newValues: [],
            };
        },

        methods: {
            close() {
                this.$emit('closeModal');
            },

            save() {
                this.$emit('save');
            },

            label(key) {
                return this.fieldLabels
                    ? (this.fieldLabels[key] ? this.fieldLabels[key] : _.startCase(key))
                    : _.startCase(key);
            },

            hasPermission(action) {
                const label = action + '-' + this.singular;

                if (this.permissions[label] == 'all') {
                    return true;
                } else if (this.permissions[label] == 'own') {
                    if (this.values.user_id) {
                        return this.values.user_id == this.currentUser.id;
                    } else {
                        // empty user_id, which means this is a new item
                        return true;
                    }
                }

                return false;
            },
        },
    };
</script>
