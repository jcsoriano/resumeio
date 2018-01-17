<style scoped>
    .paragraph-section {
        font-size:1.25rem;
    }
</style>
<template>
    <div>
        <p :class="'paragraph-section' + (!notShowOptions ? ' with-pen-p' : '')" v-if="showParagraph" v-html="lineBreaks(value.description.text)" @click="editDescription()">
        </p>
        <textarea :id="id" type="text" class="md-textarea np" v-if="showTextarea" placeholder="Enter Something" v-model="tempValue.text" @input="changeText"></textarea>
        <div class="col-xs-12 text-lg-right" v-if="value.description.editing && !notShowOptions">
            <button class="btn btn-sm btn-primary" @click="saveNewDescription()">Save</button>
            <button class="btn btn-sm btn-secondary" @click="cancelSaveDescription()">Cancel</button>
        </div>
    </div>
</template>

<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            value: {
                type: Object,
                required: true,
            },
            id: {
                type: String,
                default: 'paragraph-' + Math.floor(Math.random() * 1000000),
            },
        },

        beforeMount() {
            this.setIfUndefined(this.value, 'disableDrag', false);
            this.setIfUndefined(this.value, 'editName', false);
            this.setIfUndefined(this.value, 'question', false);
            this.setIfUndefined(this.value.description, 'editing', false);
            this.setIfUndefined(this.value.description, 'saved', false);
        },

        data() {
            return {
                tempValue: {
                    text: this.value.description.text,
                    saved: false,
                    editing: true,
                },
                disableDrag: false,
            };
        },

        computed: {
            showParagraph() {
                if (!this.value.question) {
                    return !this.value.description.editing || this.notShowOptions;
                }

                // if paragraph is a question, never
                // show it for now (we'll handle reviewing later)
                return false;
            },

            showTextarea() {
                if (!this.value.question) {
                    return this.value.description.editing && !this.notShowOptions;
                }

                // if paragraph is a question, always show textarea for now
                // we'll handle reviewing later
                return true;
            },
        },

        methods: {
            setIfUndefined(obj, property, defaultValue) {
                if (obj[property] === undefined) {
                    this.$set(obj, property, defaultValue);
                }
            },

            editDescription: function () {
                if (!this.notShowOptions) {
                    this.value.description.editing = true;
                    this.tempValue = Object.assign({}, this.value.description);
                    this.$nextTick(() => {
                        document.getElementById(this.id).focus();
                        this.$emit('dragNo');
                    });
                }
            },

            saveNewDescription() {
                this.tempValue.saved = true;
                this.tempValue.editing = false;
                this.value.description.text = this.tempValue.text;
                this.value.description.saved = this.tempValue.saved;
                this.value.description.editing = this.tempValue.editing;
                this.$emit('dragYes');
            },

            cancelSaveDescription() {
                this.value.description.editing = false;
            },

            lineBreaks(text) {
                return _.escape(text).replace(/(?:\r\n|\r|\n)/g, '<br />');
            },

            changeText() {
                if (this.value.question) {
                    this.value.description.text = this.tempValue.text;
                }
            },
        },
    };
</script>