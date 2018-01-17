<template>
    <div>
        <div :id="'crud-' + resource" class="crud panel panel-default">
            <div class="panel-heading">
                <div class="pull-right">
                    <a v-for="tb in parsedTitleButtons" v-if="tb.permission && parsedPermissions[tb.permission]" data-toggle="tooltip" data-placement="top" :title="tb.title" :href="tb.link" @click="tb.action" :class="tb.class" v-html="tb.text" ></a>
                </div>
                <h4 v-text="parsedTitle"></h4>
            </div>
            <div v-show="deleteShow" class="alert alert-danger">
                <button type="button" class="close" @click="dismissDelete">
                    <span aria-hidden="true">&times;</span>
                </button>
                Are you sure you want to delete this <span v-text="singularTitle"></span>?
                <br/>
                <b v-text="deleteItem.name"></b>
                <br/>
                <button class="btn btn-warning btn-sm" @click="destroy">
                    <span class="glyphicon glyphicon-trash"></span>
                    Delete
                </button>
                <button class="btn btn-default btn-sm" @click="dismissDelete">Cancel</button>
            </div>
            <div class="panel-body">
                <collection 
                    :items="items" 
                    :resource="resource"
                    :type-map="parsedTypeMap"
                    :collection-type="collectionType"
                    :tr-type="trType"
                    :subtype="subtype" 
                    :columns="parsedColumns" 
                    :display-columns="parsedDisplayColumns"
                    :real-time-columns="parsedRealTimeColumns"
                    :heavy="isHeavy" 
                    :permissions="parsedPermissions" 
                    :buttons="parsedButtons" 
                    :per-page-class="perPageClass" 
                    :search-class="searchClass" 
                    :status-class="statusClass" 
                    :pagination-class="paginationClass" 
                    :form="parsedForm"
                    :relation-attributes="parsedRelationAttributes"
                    :sync-url="parsedSourceUrl"
                >
                </collection>
            </div>
        </div>
        <form-modal 
            :current-user="parsedCurrentUser"
            :type-map="parsedTypeMap"
            :permissions="parsedPermissions" 
            :resource="resource" 
            :show="modalShow" 
            :form="parsedForm" 
            :relation-attributes="parsedRelationAttributes"
            :values="values" 
            :action="action" 
            :errors="errors" 
            :disabled-keys="disabledKeys" 
            :field-labels="parsedFieldLabels" 
            @closeModal="closeModal"
            @save="save"
        >
        </form-modal>
    </div>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                required: true,
            },
            currentUser: {
                type: String | Object,
                required: true,
            },
            perPageClass: {
                type: String,
                default: 'hidden',
            },
            searchClass: {
                type: String,
                required: false,
                default: 'text-center',
            },
            statusClass: {
                type: String,
                required: false,
                default: 'hidden',
            },
            paginationClass: {
                type: String,
                default: '',
            },
            title: {
                type: String,
                default: '',
            },
            collectionType: {
                type: String,
                default: 'list',
            },
            trType: {
                type: String,
                default: 'content',
            },
            columns: {
                type: String | Object | Array,
            },
            displayColumns: {
                type: String | Object | Array,
            },
            realTimeColumns: {
                type: String | Object | Array,
            },
            fieldLabels: {
                type: String | Object | Array,
            },
            permissions: {
                type: String | Object | Array,
            },
            form: {
                type: String | Object | Array,
            },
            relationAttributes: {
                type: String | Object | Array,
            },
            typeMap: {
                type: String | Object,
            },
            heavy: {
                type: Boolean,
                default: false,
            },
            subtype: {
                type: String,
                default: 'default',
            },
            disableOnEdit: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            noQuick: {
                type: Boolean,
                default: false,
            },
            noFull: {
                type: Boolean,
                default: false,
            },
            noAdd: {
                type: Boolean,
                default: false,
            },
            noDelete: {
                type: Boolean,
                default: false,
            },
            noArchives: {
                type: Boolean,
                default: false,
            },
            titleButtons: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            sourceUrl: {
                type: String,
                default: '',
            },
            rowButtons: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            redirectAfterQuickAdd: {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                action: 'Create New',
                modalShow: false,
                values: {},
                items: [],
                parsedColumns: {},
                parsedDisplayColumns: [],
                parsedRealTimeColumns: [],
                parsedFieldLabels: {},
                parsedPermissions: {},
                parsedForm: {},
                parsedRelationAttributes: {},
                parsedTypeMap: {},
                router: {},
                deleteItem: {},
                deleteShow: false,
                errors: {},
                disabledKeys: [],
                recommendHeavy: false,
                parsedTitleButtons: [],
                parsedTitle: this.title ? this.title : _.startCase(this.resource),
                parsedSourceUrl: this.sourceUrl ? this.sourceUrl : this.resource,
                parsedButtons: [],
                parsedCurrentUser: Array.isArray(this.currentUser)
                    ? this.currentUser
                    : JSON.parse(this.currentUser),
            };
        },

        computed: {
            singularTitle: function () {
                return _.singularize(this.parsedTitle);
            },

            singular: function () {
                return _.singularize(this.resource);
            },

            isHeavy: function () {
                return this.heavy || this.recommendHeavy;
            },
        },

        created() {
            this.parseTitleButtons();
            this.parseButtons();

            // for(var i in this.parsedTitleButtons) {
            //     console.log(typeof this.parsedTitleButtons[i].action);
            // }

            this.router = this.$resource(url(this.resource + '{/slug}'));
            this.sync();
            this.instantiateValues();
        },

        mounted() {
            this.parseProps();
        },

        methods: {
            parseProps() {
                this.parsedColumns = Array.isArray(this.columns)
                    ? this.columns
                    : JSON.parse(this.columns);
                this.parsedDisplayColumns = Array.isArray(this.displayColumns)
                    ? this.displayColumns
                    : JSON.parse(this.displayColumns);
                this.parsedRealTimeColumns = Array.isArray(this.realTimeColumns)
                    ? this.realTimeColumns
                    : JSON.parse(this.realTimeColumns);
                this.parsedFieldLabels = Array.isArray(this.fieldLabels)
                    ? this.fieldLabels
                    : JSON.parse(this.fieldLabels);
                this.parsedPermissions = Array.isArray(this.permissions)
                    ? this.permissions
                    : JSON.parse(this.permissions);
                this.parsedForm = Array.isArray(this.form)
                    ? this.form
                    : JSON.parse(this.form);
                this.parsedRelationAttributes = Array.isArray(this.relationAttributes)
                    ? this.relationAttributes
                    : JSON.parse(this.relationAttributes);
                this.parsedTypeMap = Array.isArray(this.typeMap)
                    ? this.typeMap
                    : JSON.parse(this.typeMap);
            },

            parseTitleButtons() {
                var parsedTitleButtons = [];
                if (!this.noAdd) {
                    if (!this.noQuick) {
                        parsedTitleButtons.push({
                            title: 'Quick Add',
                            text: '<span class="glyphicon glyphicon-edit"></span>',
                            class: 'btn btn-default',
                            permission: 'create-' + this.singular,
                            link: false,
                            action: this.showCreate,
                        });
                    }

                    if (!this.noFull) {
                        parsedTitleButtons.push({
                            title: 'Full Add',
                            text: '<span class="glyphicon glyphicon-plus"></span>',
                            class: 'btn btn-info',
                            permission: 'create-' + this.singular,
                            link: url(this.resource + '/create'),
                            action: function () {
                            },
                        });
                    }
                }

                var titleButtons = Array.isArray(this.titleButtons)
                    ? this.titleButtons
                    : JSON.parse(this.titleButtons);
                this.parsedTitleButtons = parsedTitleButtons.concat(titleButtons).map((val) => {
                    if (typeof val.action != 'function') {
                        val.action = function () {
                        };
                    }

                    return val;
                });
            },

            parseButtons() {
                var buttons = [];
                if (!this.noQuick) {
                    buttons.push({
                        title: 'Quick Edit',
                        text: '<span class="glyphicon glyphicon-edit"></span>',
                        class: 'btn btn-default btn-xs',
                        permission: 'update-' + this.singular,
                        link: '',
                        action: this.quickEdit,
                    });
                }

                if (!this.noFull) {
                    buttons.push({
                        title: 'Full Edit',
                        text: '<span class="glyphicon glyphicon-pencil"></span>',
                        class: 'btn btn-info btn-xs',
                        permission: 'update-' + this.singular,
                        link: url(this.resource + '/{{ slug }}/edit'),
                        action: function () {
                        },
                    });
                }

                if (!this.noDelete) {
                    buttons.push({
                        title: 'Delete',
                        text: '<span class="glyphicon glyphicon-remove"></span>',
                        class: 'btn btn-danger btn-xs',
                        permission: 'delete-' + this.singular,
                        link: '',
                        action: this.deleteFunc,
                    });
                }

                var parsedButtons = Array.isArray(this.rowButtons)
                    ? this.rowButtons
                    : JSON.parse(this.rowButtons);
                this.parsedButtons = buttons.concat(parsedButtons).map((val) => {
                    if (typeof val.action != 'function') {
                        val.action = function () {
                        };
                    }

                    if (val.link.length > 0) {
                        //workaround to the micromustache and VueJS attribute interpolation incompatibility
                        val.link = val.link.replace('{___', '{{').replace('___}', '}}');
                    }

                    return val;
                });
            },

            sync() {
                this.$http.get(url(this.parsedSourceUrl)).then((response) => {
                    this.items = response.body.items;
                    this.recommendHeavy = response.body.recommendHeavy;
                }, (error) => {
                    this.growlError(error);
                });
            },

            instantiateValues() {
                this.action = 'Create New';
                this.values = _.mapValues(this.parsedForm, val => '');
                this.errors = _.mapValues(this.parsedForm, val => []);
                this.disabledKeys = [];
                this.saving = false;
            },

            quickEdit(item) {
                this.action = 'Update';
                this.modalShow = true;
                this.values = item;
                this.disabledKeys = Array.isArray(this.disabled)
                    ? this.disableOnEdit
                    : JSON.parse(this.disableOnEdit);
            },

            closeModal() {
                this.modalShow = false;
            },

            showCreate() {
                this.instantiateValues();
                this.modalShow = false;
                this.modalShow = true;
            },

            growlNotice(message) {
                $.growl.notice({
                    title: 'Success',
                    message: message,
                });
            },

            growlError(error) {
                App.error(error);
            },

            success(action) {
                this.closeModal();
                this.dismissDelete();
                this.growlNotice(this.singularTitle + ' ' + action);
                this.instantiateValues();
                this.sync();
            },

            deleteFunc(item) {
                this.deleteShow = true;
                this.deleteItem = item;
            },

            destroy() {
                this.deleteShow = false;

                //delete the item quickly in view, to feel quick reaction
                if (!this.isHeavy) {
                    this.items.splice(_.findIndex({ slug: this.deleteItem.slug }), 1);
                }

                this.router.delete({ slug: this.deleteItem.slug }).then((response) => {
                    if (response.body.status == 'success') {
                        this.success('deleted');
                    }
                }, (error) => {
                    this.growlError(error);

                    //oops, put it back
                    if (!this.isHeavy) {
                        this.items.push(this.deleteItem);
                    }
                });
            },

            dismissDelete() {
                this.deleteShow = false;
                this.deleteItem = {};
            },

            save() {
                this.closeModal();

                //quickEdit
                if (this.values.slug) {
                    this.router.update({ slug: this.values.slug }, this.values).then((response) => {
                        if (response.body.status == 'success') {
                            this.success('updated');
                        }
                    }, (error) => {

                        //oops, show it again
                        this.modalShow = true;
                        this.growlError(error);
                    });
                } else { //quickAdd
                    //add it immediately to make it feel quick
                    if (!this.isHeavy) {
                        this.items.push(this.values);
                    }

                    this.router.save(null, this.values).then((response) => {
                        if (this.redirectAfterQuickAdd) {
                            window.location.href = url(response.body.redirect);
                        }

                        if (response.body.status == 'success') {
                            this.success('created');
                        }
                    }, (error) => {
                        this.errors = error.body;
                        this.growlError(error);

                        //oops, remove it again
                        if (!this.isHeavy) {
                            this.items.splice(_.findIndex({ slug: this.deleteItem.slug }), 1);
                        }

                        this.modalShow = true;
                    });
                }
            },
        },
    };
</script>

