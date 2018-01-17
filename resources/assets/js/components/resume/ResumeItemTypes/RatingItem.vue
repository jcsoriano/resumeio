<template>
    <div class="skillset">
        <div class="offset-lg-1 col-lg-3 col-md-5 date-holder print-3-1">
            <p v-text="value.title" class="skill-text" v-if="(!value.editing || notShowOptions) && value.saved"></p>
            <input type="text" class="small" v-if="value.editing && !notShowOptions" v-model="tempValue.title" :placeholder="'Enter '+bulletName">
        </div><!--
    --><div v-if="(!value.editing || notShowOptions) && value.saved" class="description-holder print-8" :class="value.editing && notShowOptions ? 'col-lg-8 col-md-7' : 'col-lg-7'">
            <star-rating v-model="!value.editing || notShowOptions ? value.rating : tempValue.rating" @input="ratingChanged" :read-only="readOnlyRating" :increment="0.5"></star-rating>
        </div>
        <div  v-if="value.editing && !notShowOptions" class="description-holder" :class="value.editing && notShowOptions ? 'col-lg-8' : 'col-lg-7'">
            <star-rating v-model="!value.editing || notShowOptions ? value.rating : tempValue.rating" @input="ratingChanged" :read-only="readOnlyRating" :increment="0.5"></star-rating>
        </div>
        <div class="col-lg-1" v-if="value.editing == false && !notShowOptions">
            <button class="btn btn-primary smallbtns" @click="editRating(value)"><i class="fa fa-pencil-square-o"></i></button>
            <button class="btn btn-danger smallbtns" @click="removeItem()"><i class="fa fa-remove"></i></button>
        </div>
        <div class="col-lg-12 text-xs-center" v-if="value.editing && !notShowOptions"> 
            <button class="btn btn-primary btn-sm" @click="saveRating()">Save</button>
            <button class="btn btn-secondary btn-sm" @click="cancelRating()">Cancel</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            value: {
                type: Object,
                required: true,
            },
            itemType: {
                type: String,
            },
            bulletName: {
                type: String,
                default: 'Title',
            },
            question: {
                type: Boolean,
                default: false,
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editing', false);
            this.setValueIfUndefined('saved', true);
        },

        data() {
            return {
                tempValue: {
                    editing: true,
                    title: '',
                    rating: 1,
                    saved: false,
                },
            };
        },

        computed: {
            readOnlyRating() {
                if (!this.question) {
                    // the rating will be read-only IF NOT EDITING OR IN PREVIEW MODE
                    return !this.value.editing || this.notShowOptions;
                }

                // if it's a question, the rating will always be editable!
                return false;
            },
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            emptyTempValue() {
                this.tempValue = {
                    editing: true,
                    title: '',
                    rating: 1,
                    saved: false,
                };
            },

            saveRating() {
                this.tempValue.saved = true;
                this.tempValue.editing = false;

                this.value.editing = this.tempValue.editing;
                this.value.title = this.tempValue.title;
                this.value.rating = this.tempValue.rating;
                this.value.saved = this.tempValue.saved;

                this.emptyTempValue();
            },

            editRating() {
                if (!this.notShowOptions) {
                    this.tempValue = Object.assign({}, this.value);
                    this.tempValue.editing = true;
                    this.value.editing = this.tempValue.editing;
                }
            },

            cancelRating() {
                if (this.value.saved) {
                    this.value.editing = false;
                }else {
                    this.$emit('removeItem');
                }
            },

            removeItem() {
                this.$emit('removeItem');
            },

            ratingChanged(value) {
                // workaround to weird question glitch
                if (this.question) {
                    this.value.rating = value;
                }
            },
        },
    };
</script>