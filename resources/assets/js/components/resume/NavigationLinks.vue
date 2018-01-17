<template>
	<div>
        <div class="pos-rel">
           <div class="nav-holder">
                <ul class="pull-left" v-if="left != 0">
                    <li v-for="link in left">
                        <a v-if="link.type == 'component'" @click="showComponentActionModal(link.component)" v-text="link.name"></a>
                        <a v-if="link.type == 'link'" :href="link.link" v-text="link.name"></a>
                        <a v-if="link.type == 'function'" @click="link.function" v-text="link.name"></a>
                        <span v-if="link.type == 'show'">
                            <a v-show="activePage != link.slug" @click="showPage(link.slug)" v-text="link.name"></a>
                            <a v-show="activePage == link.slug" @click="showPage(link.back.slug)" v-text="link.back.name"></a>
                        </span>
                        
                    </li>
                </ul>
                <ul class="pull-right" v-if="right.length != 0">
                    <li v-for="link in right">
                        <a v-if="link.type == 'component'" @click="showComponentActionModal(link.component)" v-text="link.name"></a>
                        <a v-if="link.type == 'link'" :href="link.link" v-text="link.name"></a>
                        <a v-if="link.type == 'function'" @click="link.function" v-text="link.name"></a>
                        <span v-if="link.type == 'show'">
                            <a v-show="activePage != link.slug" @click="showPage(link.slug)" v-text="link.name"></a>
                            <a v-show="activePage == link.slug" @click="showPage(link.back.slug)" v-text="link.back.name"></a>
                        </span>
                    </li>
                </ul>
           </div>
        </div>
        <component v-for="ca in componentActions" :is="ca" :id="'navigation-link-' + ca" :show="showingComponentActionModal[ca]" @close="closeComponentActionModal(ca)" :base-slug="slug" @verificationRequest="requestVerification">
        </component>
	</div>
</template>

<script>
    export default {
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            left: {
                type: Array,
                default: [],
            },
            right: {
                type: Array,
                default: [],
            },
            slug: {
                type: String,
                default: '',
            },
            activePage: {
                type: String,
                default: '',
            },
            intent: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                showingComponentActionModal: {},
            };
        },

        beforeMount() {
            this.initializeShowingActionsModal();
        },

        mounted() {
            if (this.intent == 'login') {
                this.showingComponentActionModal['log-in'] = true;
            }
        },

        watch: {
            componentActions() {
                this.initializeShowingActionsModal();
            },
        },

        computed: {
            componentActions() {
                let linksWithComponentActions = this.getLinksWithComponentActions(this.left)
                    .concat(this.getLinksWithComponentActions(this.right));
                return linksWithComponentActions.map(link => link.component);
            },
        },

        methods: {
            initializeShowingActionsModal() {
                for (let i in this.componentActions) {
                    this.$set(this.showingComponentActionModal, this.componentActions[i], false);
                }
            },

            getLinksWithComponentActions(links) {
                return links.filter(link => link.type == 'component');
            },

            showComponentActionModal(componentAction) {
                this.showingComponentActionModal[componentAction] = true;
            },

            closeComponentActionModal(componentAction) {
                this.showingComponentActionModal[componentAction] = false;
            },

            showPage(slug) {
                this.$emit('show', { slug });
            },

            requestVerification() {
                this.$emit('verificationRequest');
            },
        },
    };
</script>