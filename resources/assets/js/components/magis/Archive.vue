<template>
    <div>
        <div :id="'archive-' + resource" class="archive panel panel-default">
            <div class="panel-heading">
                <div class="pull-right">
                    <a v-for="tb in titleButtons" v-if="tb.permission && permissions[tb.permission]" data-toggle="tooltip" data-placement="top" :title="tb.title" :href="tb.link" @click="tb.action" :class="tb.class" v-html="tb.text" ></a>
                </div>
                <h4 v-text="title"></h4>
            </div>
            <div v-show="restoreShow" class="alert alert-danger">
                <button type="button" class="close" @click="dismissRestore">
                    <span aria-hidden="true">&times;</span>
                </button>
                Are you sure you want to restore this <span v-text="singularTitle"></span>?
                <br/>
                <b v-text="restoreItem.name"></b>
                <br/>
                <button class="btn btn-warning btn-sm" @click="restore">
                    <span class="glyphicon glyphicon-trash"></span>
                    Restore
                </button>
                <button class="btn btn-default btn-sm" @click="dismissRestore">Cancel</button>
            </div>
            <div class="panel-body">
                <collection :items="items" :syncUrl="resource + '/archives'" :resource="resource" :type="type" :subtype="subtype" :columns="columns" :heavy="isHeavy" :permissions="permissions" :buttons="buttons" :per-page-class="perPageClass" :search-class="searchClass" :status-class="statusClass" :pagination-class="paginationClass" :form="form"></collection>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                required: true
            },
            perPageClass: {
                type: String,
                default: 'hidden'
            },
            searchClass: {
                type: String,
                required: false,
                default: 'text-center'
            },
            statusClass: {
                type: String,
                required: false,
                default: 'hidden'
            },
            paginationClass: {
                type: String,
                default: ''
            },
            type: {
                type: String,
                default: 'list'
            },
            heavy: {
                type: Boolean,
                default: false
            },
            subtype: {
                type: String,
                default: 'default'
            },
            titleButtons: {
                type: Array,
                default: function () {
                    var titleButtons = [{
                        title: 'Back to Manage ' + this.title,
                        text: '<span class="glyphicon glyphicon-chevron-left"></span> Back',
                        class: 'btn btn-info',
                        permission: 'manage-' + this.pluralMainObject,
                        link: url(this.pluralMainObject + '/manage'),
                        action: function () {
                            
                        }
                    }];
                    return titleButtons;
                }
            },
            buttons: {
                type: Array,
                default: function () {
                    var buttons = [{
                        title: 'Restore',
                        text: 'Restore',
                        class: 'btn btn-danger btn-xs',
                        permission: 'restore-' + this.singular,
                        link: '',
                        action: this.showRestore
                    }];

                    return buttons;
                }
            },
        },
        data() {
            return {
                action: 'Create New',
                values: {},
                items: [],
                columns: {},
                form: {},
                router: {},
                restoreItem: {},
                restoreShow: false,
                errors: {},
                disabledKeys: [],
                permissions: {},
                recommendHeavy: false,
            }
        },
        computed: {
            title: function () {
                return _.titleize(this.singular) + ' Archives';
            },
            mainObject: function () {
                return this.resource.replace('archives', '');
            },
            pluralMainObject: function () {
                return _.pluralize(this.mainObject);
            },
            singularTitle: function () {
                return _.singularize(this.title);
            },
            singular: function () {
                return _.singularize(this.resource);
            },
            isHeavy: function () {
                return this.heavy || this.recommendHeavy;
            }
        },
        created() {
            this.router = this.$resource(url(this.resource + '{/slug}'));
            this.sync();
        },
        methods: {
            sync() {
                this.$http.get(url(this.resource + '/archives')).then(function(response) {
                    this.items = response.body.items;
                    this.recommendHeavy = response.body.recommendHeavy;
                    this.columns = response.body.columns;
                    this.permissions = response.body.permissions;
                    this.form = response.body.form;
                }.bind(this), function(error) {
                    this.growlError(error);
                }.bind(this));
            },
            restore() {
                this.restoreShow = false;
                //delete the item quickly in view, to feel quick reaction
                if (!this.isHeavy) {
                    this.items.splice(_.findIndex({slug: this.restoreItem.slug}), 1);
                }
                this.$http.post(url(this.resource + '/restore/' + this.restoreItem.slug), {}).then(function(response) {
                    if (response.body.status == 'success') {
                        this.success('restored');
                    }
                }.bind(this), function(error) {
                    this.growlError(error);
                    //oops, put it back
                    if (!this.isHeavy) {
                        this.items.push(this.restoreItem);
                    }
                }.bind(this));
            },
            showRestore(item) {
                this.restoreShow = true;
                this.restoreItem = item;
            },
            dismissRestore() {
                this.restoreShow = false;
                this.restoreItem = {};
            },
            growlNotice(message) {
                $.growl.notice({ 
                    title: 'Success',
                    message: message 
                });
            },
            growlError(error) {
                $.growl.error({ 
                    title: 'Error',
                    message: 'Something went wrong. Please check your internet connection and reload the page.' 
                });
                console.log(error);
            },
            success(action) {
                this.dismissRestore();
                this.growlNotice(this.singularTitle + ' ' + action);
                this.sync();
            },
        }
    }
</script>

