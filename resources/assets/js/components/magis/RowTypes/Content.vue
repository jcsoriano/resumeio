<style scoped>
    .img-responsive {
        max-width:100px;
    }
</style>

<template>
    <tr :class="(item['is_draft'] ? 'warning' : '') + ' ' + (item['published_at'] && isScheduled(item['published_at']) ? 'info' : '') + ' ' + additionalClass">
        <td v-for="c in columns" >
            <real-time
                v-if="_.indexOf(realTimeColumns, c) >= 0"
                :resource="resource"
                :name="c"
                v-model="item"
                :type-map="typeMap"
                :type="formData[c]"
                :relation-attribute="relationAttributes[c]"
            >
            </real-time>
            <div v-else>
                <magis-img class="img-responsive" v-if="formData[c] == 'photo' && item[c]" :src="url(item[c])" width="100" />
                <span v-if="formData[c] == 'many-relation' && item[c]" v-for="i in item[c]" class="label label-primary" v-text="relationAttributes[c] ? i[relationAttributes[c]] : i.name"></span>
                <span v-if="formData[c] == 'one-relation' && item[c]" v-text="relationAttributes[c] ? item[c][relationAttributes[c]] : item[c].name"></span>
                <span v-if="_.indexOf(['photo', 'many-relation', 'one-relation'], formData[c]) < 0" v-text="item[c]"></span>
                <span v-if="c == 'name' && item['is_draft']" class="label label-warning">Draft</span>
                <span v-if="c == 'published_at' && isScheduled(item['published_at'])" class="label label-info">Scheduled</span>
            </div>
        </td>
        <td v-if="buttons">
            <a v-for="b in buttons" v-if="b.permission && permissions[b.permission]" data-toggle="tooltip" data-placement="top" :title="b.title" :href="b.link ? micromustache.render(b.link, item) : false" @click="b.action(item)" :class="b.class" v-html="b.text" ></a>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true,
            },
            resource: {
                type: String,
                required: false,
            },
            typeMap: {
                type: Object,
                required: function () {
                    return {};
                },
            },
            additionalClass: {
                type: String,
                default: '',
            },
            columns: {
                type: Object | Array,
                required: true,
            },
            realTimeColumns: {
                type: Array | String,
                default: function () {
                    return [];
                },
            },
            formData: {
                type: Object | Array,
                required: true,
            },
            relationAttributes: {
                type: Object | Array,
                default: function () {
                    return {};
                },
            },
            permissions: {
                type: Object,
                required: true,
            },
            buttons: {
                type: Array,
                default: function () {
                    return [];
                },
            },
        },

        data() {
            return {
                micromustache: micromustache,
            };
        },

        computed: {
            singular() {
                return _.singularize(this.resource);
            },
        },

        methods: {
            url(path) {
                return url(path);
            },

            isScheduled(publishedAt) {
                return moment().isBefore(publishedAt);
            },

            cascadeUp(val) {
                this.$emit('input', val);
            }
        },
    };
</script>
