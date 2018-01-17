<style scoped>
    th .hover-only {
        opacity: 0;
    }

    th:hover .hover-only {
        opacity: 1;
    }

    th .sort-active {
        opacity:1;
    }
</style>

<style>
    .list-complete-item {
        transition: all 1s;
    }

    .list-complete-enter, .list-complete-leave-active {
        opacity: 0;
    }
</style>

<template>
    <div>
        <div class="col-xs-12">
            <button class="btn btn-default btn-xs pull-right" @click.prevent="showChooseColumnsModal">
                <span class="glyphicon glyphicon-th-list"></span> Choose Columns
            </button>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th v-for="c in shownColumns" @click="sortBy(c)">
                                    <span :class="'pull-right hover-only glyphicon glyphicon-sort-by-attributes' + (sortedBy == c && !sortAsc ? '-alt' : '') + ' ' + (sortedBy == c ? 'sort-active' : '')"></span>
                                    <span v-text="columns[c]"></span>
                                </th>
                                <th v-if="buttons">
                                    Options
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items"
                                :resource="resource"
                                :type-map="typeMap"
                                :is="'tr-' + trType" 
                                :item="item"
                                :columns="shownColumns"
                                :form-data="form"
                                :relation-attributes="relationAttributes"
                                :real-time-columns="realTimeColumns"
                                :permissions="permissions"
                                :buttons="buttons"
                            >
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th v-for="c in shownColumns" v-text="columns[c]"></th>
                                <th v-if="buttons">
                                    Options
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <modal
            :id="'choose-columns-modal-' + this.resource"
            :show="choosingColumns"
            @close="hideChooseColumnsModal"
        >
            <h4 slot="modal-title">Choose Columns</h4>
            <div slot="modal-body" class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <ul>
                            <li>Drag a column from left to right to add it to the displayed columns.</li>
                            <li>Drag a column from right to left to hide it.</li>
                            <li>Reorder a column by dragging it to your desired place.</li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <h4>Hidden Columns</h4>
                        <ul class="list-group">
                            <draggable :list="hiddenColumns" :options="{group: 'columns'}">
                                <transition-group name="list" tag="div" style="min-height:100px;">
                                    <li v-for="c in hiddenColumns" class="list-group-item" :key="c" v-text="columns[c]"></li>
                                </transition-group>
                            </draggable>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <h4>Shown Columns</h4>
                        <ul class="list-group">
                            <draggable :list="shownColumns" :options="{group: 'columns'}">
                                <transition-group name="list" tag="div" style="min-height:100px;">
                                    <li v-for="c in shownColumns" class="list-group-item" :key="c" v-text="columns[c]"></li>
                                </transition-group>
                            </draggable>
                        </ul>
                    </div>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="hideChooseColumnsModal">Done</button>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                required: true,
            },
            typeMap: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            trType: {
                type: String,
                default: 'content',
            },
            form: {
                type: Object,
                required: true,
            },
            relationAttributes: {
                type: Object | Array,
                default: {},
            },
            columns: {
                type: Object,
                required: true,
            },
            displayColumns: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            realTimeColumns: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            resource: {
                type: String,
                required: true,
            },
            quickEdit: {
                type: Function,
                default: function () {
                },
            },
            permissions: {
                type: Object,
                required: true,
            },
            deleteFunc: {
                type: Function,
                default: function () {
                },
            },
            buttons: {
                type: Array,
                default: function () {
                    return [];
                },
            },
            sortedBy: {
                type: String,
                default: '',
            },
            sortBy: {
                type: Function,
                default: function () {
                },
            },
        },

        data() {
            return {
                choosingColumns: false,
                shownColumns: [],
                hiddenColumns: [],
            };
        },

        mounted() {
            this.parseDisplayColumns();
        },

        watch: {
            displayColumns(newVal, oldVal) {
                if (!_.isEqual(newVal, oldVal)) {
                    this.parseDisplayColumns();
                }
            },
        },

        methods: {
            parseDisplayColumns() {
                const shownColumns = Array.isArray(this.displayColumns)
                    ? this.displayColumns
                    : JSON.parse(this.displayColumns);
                this.shownColumns = shownColumns;
                this.hiddenColumns = _.filter(
                    Object.keys(this.columns),
                    column => shownColumns.indexOf(column) < 0
                );
            },

            titleize(str) {
                return _.titleize(str);
            },

            showChooseColumnsModal() {
                this.choosingColumns = true;
            },

            hideChooseColumnsModal() {
                this.choosingColumns = false;
            },
        },
    };
</script>