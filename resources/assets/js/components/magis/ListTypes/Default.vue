<template>
    <div class="media">
        <div v-if="item.photo" class="media-left col-sm-3">
            <img class="img-responsive" :src="asset(item.photo)">
        </div>
        <div class="media-body">
            <div class="btn-group btn-group-xs pull-right">
                <a v-for="b in buttons" v-if="b.permission && permissions[b.permission]" data-toggle="tooltip" data-placement="top" :title="b.title" :href="b.link ? micromustache.render(b.link, item) : '#'" @click="b.action(item)" :class="b.class" v-html="b.text" ></a>
            </div>
            <h4 class="media-heading" v-text="item.name"></h4>
            <p v-text="item.description"></p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            },
            resource: {
                type: String,
                required: true
            },
            permissions: {
                type: Object,
                required: true
            },
            buttons: {
                type: Array,
                default: function () {
                    return [];
                }
            }
        },
        data() {
            return {
                micromustache: micromustache,
            }
        },
        computed: {
            singular() {
                return _.singularize(this.resource);
            }
        },
        methods: {
            asset(url) {
                return url(url);
            }
        }
    }
</script>
